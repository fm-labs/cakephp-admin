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
<div style="text-align: left">
    <hr />
    <?php if ($identity) : ?>
        IdentityID: <?= h($identity->getIdentifier()); ?><br />
        IdentityDataClass: <?= h(get_class($identity->getOriginalData())); ?><br />
        IdentityProvider: <?= h($identityProvider ? get_class($identityProvider) : '-'); ?><br />
        IdentityAttr: <?= h($identityAttr); ?><br />
        IdentityClass: <?= h($identityClass) . " - Actual: " . get_class($identity); ?><br />
        AuthProvider: <?= h(get_class($authProvider)); ?><br />
    <?php endif; ?>
</div>
