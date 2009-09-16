<?php require(__INCLUDES__ . '/header.inc.php'); ?>

	<div class="searchBar">
		<div class="title">
			<span style="font-weight: normal; font-size: 12px;">Issue #<?php _p($this->objIssue->Id); ?>: </span>
			<?php _p($this->objIssue->Title); ?>
		</div>
		<div class="right">
			<?php // $this->txtSearch->Render(); ?>
		</div>
		<div class="right">
			<?php // $this->btnSearchAll->Render(); ?>
		</div>
		<div class="right">
			<?php // $this->btnSearchThis->Render(); ?>
		</div>
	</div>

	<div style="float: left; width: 230px;">
		<?php $this->pnlDetails->Render(); ?>
<?php if ($this->objIssue->IsEditableForPerson(QApplication::$Person)) { ?>
		<div class="roundedLink" style="margin-left: 53px;"><a href="/issues/edit.php/<?php _p($this->objIssue->Id); ?>" title="Edit This Issue" style="width: 110px; text-align: center;">Edit Issue</a></div><br clear="all"/><br/>
<?php } ?>
		<?php $this->pnlVotes->Render(); ?>

		<?php $this->pnlExampleCode->Render(); ?>
		<?php $this->pnlExampleTemplate->Render(); ?>
		<?php $this->pnlExampleData->Render(); ?>
		<?php $this->pnlExpectedOutput->Render(); ?>
		<?php $this->pnlActualOutput->Render(); ?>
	</div>

	<?php $this->pnlMessages->Render(); ?>
	<br clear="all"/>

<?php require(__INCLUDES__ . '/footer.inc.php'); ?>