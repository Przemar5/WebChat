<?php $this->startSection('head'); ?>

<?php $this->endSection(); ?>

<?php $this->startSection('body'); ?>

Registration

<form method="POST">
	<label>
		Email
		<input type="email" name="email" value="<?= $_POST['email'] ?? ''; ?>">
	</label>
	<label>
		Password
		<input type="password" name="password" value="<?= $_POST['password'] ?? ''; ?>">
	</label>
	<button type="submit">Send</button>
</form>

<?php $this->endSection(); ?>