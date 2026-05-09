<form action="/code/verify" method="post">
    <label for="code">Enter your code:</label>
    <input type="text" id="code" name="code" required>
    <button type="submit">Redeem</button>
</form>
<?php if (isset($status)) {
    if ($status === 0) { ?>
        <p style="color: red;"><?= $message ?></p>
    <?php } else { ?>
        <p style="color: green;"><?= $message ?></p>
    <?php }
} ?>
    <?= $this->include('bar/sidebar') ?>
