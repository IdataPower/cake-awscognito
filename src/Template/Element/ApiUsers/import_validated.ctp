<?php
$validated_api_users       = array_filter($api_users, function($u){ return !$u->getErrors(); });
$validated_api_users_count = count($validated_api_users);
$new_api_users_count       = count(array_filter($validated_api_users, function($u){ return !$u->id; }));
$edit_api_users_count      = count(array_filter($validated_api_users, function($u){ return $u->id; }));
$invalid_api_users_count   = count(array_filter($api_users, function($u){ return $u->getErrors(); }));
?>

<?php
$fields = $fields ?? [
    'id'                   => __d('PowerSystem/CognitoSDK', 'Id'),
    'aws_cognito_username' => __d('PowerSystem/CognitoSDK', 'Aws Cognito Username'),
    'email'                => __d('PowerSystem/CognitoSDK', 'Email') ,
    'first_name'           => __d('PowerSystem/CognitoSDK', 'First Name'),
    'last_name'            => __d('PowerSystem/CognitoSDK', 'Last Name'),
];
?>

<style type="text/css">
    .table.users-import td{
        border-top: none;
        vertical-align: middle;
    }
    .table.users-import tbody td:first-child,
    .table.users-import thead th:first-child {
        width: 10px;
    }
    .card .table.users-import tbody td:first-child,
    .card .table.users-import thead th:first-child {
        padding-left: initial;
    }
    .note{
        margin-top: 8px;
    }
</style>

<div class="APIUsers-validated-import container">
    <?= $this->Form->create($api_users, ['align' => 'inline', 'method' => 'post']) ?>
    <?= $this->Form->hidden('csv_data', ['value' => $csv_data]) ?>
    <?= $this->Form->hidden('save_rows', ['value' => 1]) ?>
    <div class="card">
        <div class="header">
            <h3 class="title"><?= __d('PowerSystem/CognitoSDK', 'Import API Users') ?></h3>
        </div>
        <div class="content">
            <table class="table users-import">
                <tbody>
                    <tr>
                        <td class="text-right lead">
                            <strong><?= $rows_count ?></strong>
                        </td>
                        <td>
                            <span class="text-muted text-uppercase">
                                <?= __d('PowerSystem/CognitoSDK', 'Total Submitted Users') ?>
                            </span>
                        </td>
                    </tr>
                    <?php if($stopped_at_max_errors): ?>
                     <tr>
                        <td class="text-right lead">
                            <strong><?= $max_errors ?></strong>
                        </td>
                        <td>
                            <span class="text-muted text-uppercase">
                                <?= __d('PowerSystem/CognitoSDK', 'Errors Before Validation Stopped') ?>
                            </span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-right lead">
                            <strong><?= $analyzed_rows_count ?></strong>
                        </td>
                        <td>
                            <span class="text-muted text-uppercase">
                                <?= __d('PowerSystem/CognitoSDK', 'Validated Users') ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-right lead text-success" width="10">
                            <strong><?= $validated_api_users_count ?></strong>
                        </td>
                        <td>
                            <span class="text-muted text-uppercase text-success">
                                <?= __d('PowerSystem/CognitoSDK', 'Valid API Users') ?>
                                <i class="ion ion-checkmark-circled"></i>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <a data-toggle="modal" href="#" data-target="#modalValidated">
                                <?= __d('PowerSystem/CognitoSDK', 'Show List of Valid API Users') ?> &raquo;
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <div class="form-group">
                <button type="submit" role="button" class="btn btn-lg btn-primary">
                    <?= __d('PowerSystem/CognitoSDK', 'Save changes') ?>
                </button>
                <?= $this->Html->link(__d('PowerSystem/CognitoSDK', 'Cancel'), ['action' => 'import'], ['class' => 'btn btn-link']) ?>
            </div>
            <div class="text-muted note">
                <span class="glyphicon glyphicon-info-sign"></span>
                <strong><?= __d('PowerSystem/CognitoSDK', 'Note') ?>:</strong>
                <?= __d('PowerSystem/CognitoSDK', 'Keep in mind that proceeding will send emails to all new users with their login details.') ?>
            </div>
        </div>
    </div>

    <?php if($invalid_api_users_count): ?>
    <div class="card">
        <div class="header">
            <h3 class="title"><?= __d('PowerSystem/CognitoSDK', 'Validation Errors') ?></h3>
            <p><?= __d('PowerSystem/CognitoSDK', '{0} Users did not pass the validation stage. Please review the errors and try again.', $invalid_api_users_count) ?></p>
        </div>
        <div class="content">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><?= __d('PowerSystem/CognitoSDK', 'Row') ?></th>
                        <?php foreach ($fields as $label): ?>
                        <th><?= $label ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($api_users as $key => $api_user): ?>
                    <?php if(!$api_user->getErrors()) continue; ?>
                    <tr>
                        <td><?= $key ?></td>
                        <?php foreach($fields as $field => $label): ?>
                        <td class="<?= $api_user->getError($field) ? 'bg-danger' : '' ?>">
                            "<?= $api_user->get($field) ?>"
                            <span class="has-error">
                                <?= $this->Form->error("$key.$field") ?>
                            </span>
                        </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="footer">
            <?= $this->Html->link(
                '<i class="ion ion-chevron-left"></i> &nbsp;'.
                __d('PowerSystem/CognitoSDK', 'Back to API Users Import Form'),
                ['action' => 'import'],
                ['escape' => false]
            ) ?>
        </div>
    </div>
    <?php endif; ?>

<?= $this->Form->end() ?>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modalValidated">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><?= __d('PowerSystem/CognitoSDK', 'Valid API Users') ?></h4>
      </div>
      <div class="modal-body">
        <table class="table users-import">
            <tbody>
                <tr>
                    <td class="text-right lead">
                        <strong><?=  $new_api_users_count ?></strong>
                    </td>
                    <td>
                        <span class="text-muted text-uppercase">
                            <?= __d('PowerSystem/CognitoSDK', 'New API Users will be added.') ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="text-right lead">
                        <strong><?=  $edit_api_users_count ?></strong>
                    </td>
                    <td>
                        <span class="text-muted text-uppercase">
                            <?= __d('PowerSystem/CognitoSDK', 'API Users will be edited.') ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-hover">
            <thead>
                <tr>
                    <?php foreach($fields as $label): ?>
                        <th><?= $label ?></th>
                    <?php endforeach; ?>
                    <th><?= __d('PowerSystem/CognitoSDK', 'Action') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($validated_api_users as $api_user): ?>
                <tr>
                    <?php foreach($fields as $field => $label): ?>
                    <td class="<?= $api_user->isDirty($field) ? 'bg-success' : '' ?>">
                        <?= $api_user->get($field) ?>
                    </td>
                    <?php endforeach; ?>
                    <td>
                        <?php if(!$api_user->isDirty()): ?>
                            <?= __d('PowerSystem/CognitoSDK', 'Unchanged') ?>
                        <?php elseif(!empty($api_user->id)): ?>
                            <?= __d('PowerSystem/CognitoSDK', 'Edit') ?>
                        <?php else: ?>
                            <?= __d('PowerSystem/CognitoSDK', 'Add') ?>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
