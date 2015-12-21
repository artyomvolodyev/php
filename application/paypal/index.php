<?php

require 'src/start.php';

?>

<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>

<?php if($user->member): ?>
    <p>You are a member</p>
    <?php else: ?>
    <p>You are not a member. <a href="member/payment.php">Become a member</a></p>
<?php endif; ?>

</body>
</html>