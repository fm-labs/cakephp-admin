<?php
/**
 * @var object|null $user
 * @var \Authentication\IdentityInterface $identity
 * @var \Authentication\AuthenticationService $authentication
 */
$identity = $this->getRequest()->getAttribute('identity');
$authentication = $this->getRequest()->getAttribute('authentication');
$authProvider = $authentication->getAuthenticationProvider();
$identityProvider = $authentication->getIdentificationProvider();
$identityAttr = $authentication->getIdentityAttribute();
$identityClass = $authentication->getConfig('identityClass');
?>
<?php $this->Breadcrumbs->add(__d('admin', 'User'), ['action' => 'user']); ?>
<?php $this->assign('title', $user['name']); ?>
<div class="users view container" style="text-align: center;">
    <h2>
        <?= h($user['name']); ?>
    </h2>

    <div style="text-align: left">
    <?php if ($identity) : ?>
        IdentityID: <?= h($identity->getIdentifier()); ?><br />
        IdentityDataClass: <?= h(get_class($identity->getOriginalData())); ?><br />
        IdentityProvider: <?= h($identityProvider ? get_class($identityProvider) : '-'); ?><br />
        IdentityAttr: <?= h($identityAttr); ?><br />
        IdentityClass: <?= h($identityClass) . " - Actual: " . get_class($identity); ?><br />
        AuthProvider: <?= h(get_class($authProvider)); ?><br />
    <?php endif; ?>
    </div>

    <div>
        <div class="image" style="margin: 1em 0;">
            <?= $this->Ui->icon('user-circle', ['class' => 'fa-5x']); ?>
        </div>
        <div class="content">
            <p class="description">
                Email: <?= h($user['email']); ?>
            </p>

            <p class="meta">
                <span class="date">Joined <?= h($user['created']->nice()) ?></span>
            </p>
        </div>
        <div class="extra content">
            <?=
            $this->Ui->link(
                __d('admin', 'Goto Dashboard'),
                ['_name' => 'admin:system:dashboard'],
                ['icon' => 'home', 'class' => 'btn btn-default btn-block']
            ); ?>
            <?=
            $this->Ui->link(
                __d('admin', 'Edit profile'),
                ['_name' => 'admin:system:user:profile'],
                ['icon' => 'edit', 'class' => 'btn btn-default btn-block']
            ); ?>
            <?=
            $this->Ui->link(
                __d('admin', 'Change password'),
                ['_name' => 'user:passwordchange'],
                ['icon' => 'key', 'class' => 'btn btn-default btn-block']
            ); ?>
            <?=
            $this->Ui->link(
                __d('admin', 'Logout'),
                ['_name' => 'admin:system:user:logout'],
                ['icon' => 'sign-out', 'class' => 'btn btn-default btn-block']
            ); ?>
        </div>
    </div>
</div>
