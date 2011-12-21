<?php
	$dttLocalize = QApplication::LocalizeDateTime($_ITEM->PostDate);
?>
<div class="messageBar <?php if ($_CONTROL->CurrentItemIndex % 2) print 'messageBarAlternate'; ?>">
	<a name="<?php _p($_ITEM->ReplyNumber); ?>"></a>
	<div class="name">
<?php
	if ($_CONTROL->ParentControl->IsMessageEditable($_ITEM)) {
		printf('<span class="replyNumber">#%s (<a href="#" title="Edit This Post" %s>Edit</a>) &nbsp;|&nbsp; </span>',
			$_ITEM->ReplyNumber, $_CONTROL->ParentControl->pxyEditMessage->RenderAsEvents($_ITEM->Id, false));
	} else {
		printf('<span class="replyNumber">#%s &nbsp;|&nbsp; </span>', $_ITEM->ReplyNumber);
	}
?>
	<?php _p(($_ITEM->Person) ? $_ITEM->Person->DisplayForForums : '&lsaquo;&lsaquo; Qcodo System Message &rsaquo;&rsaquo;', false); ?>
	</div>
	<div class="date">
	<?php if ($_CONTROL->ParentControl->pxyDeleteMessage) { ?>
		<a style="margin-right: 25px;" href="#" <?php $_CONTROL->ParentControl->pxyDeleteMessage->RenderAsEvents($_ITEM->Id); ?>>Delete</a>
	<?php } ?>
	<?php _p($dttLocalize->__toString('DDDD, MMMM D, YYYY, h:mm zz')); ?>
	<?php QApplication::DisplayTimezoneLink($dttLocalize); ?>
	</div>
</div>
<div class="messageBody <?php if ($_CONTROL->CurrentItemIndex % 2) print 'messageBodyAlternate'; ?>">
	<div class="textStyle"><?php _p($_ITEM->CompiledHtml, false); ?></div>
</div>
