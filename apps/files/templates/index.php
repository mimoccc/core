<!--[if IE 8]><style>input[type="checkbox"]{padding:0;}table td{position:static !important;}</style><![endif]-->
<div id="controls">
	<?php print_unescaped($_['breadcrumb']); ?>
		<div class="actions creatable <?php if (!$_['isCreatable']):?>hidden<?php endif; ?> <?php if (isset($_['files']) and count($_['files'])==0):?>emptycontent<?php endif; ?>">
			<div id="new" class="button">
				<a><?php p($l->t('New'));?></a>
				<ul>
					<li style="background-image:url('<?php p(OCP\mimetype_icon('text/plain')) ?>')"
						data-type='file'><p><?php p($l->t('Text file'));?></p></li>
					<li style="background-image:url('<?php p(OCP\mimetype_icon('dir')) ?>')"
						data-type='folder'><p><?php p($l->t('Folder'));?></p></li>
					<li style="background-image:url('<?php p(OCP\image_path('core', 'filetypes/web.svg')) ?>')"
						data-type='web'><p><?php p($l->t('From link'));?></p></li>
				</ul>
			</div>
			<div id="upload" class="button"
				 title="<?php p($l->t('Upload') . ' max. '.$_['uploadMaxHumanFilesize']) ?>">
				<form data-upload-id='1'
					  id="data-upload-form"
					  class="file_upload_form"
					  action="<?php print_unescaped(OCP\Util::linkTo('files', 'ajax/upload.php')); ?>"
					  method="post"
					  enctype="multipart/form-data"
					  target="file_upload_target_1">
					<?php if($_['uploadMaxFilesize'] >= 0):?>
					<input type="hidden" name="MAX_FILE_SIZE" id="max_upload"
						   value="<?php p($_['uploadMaxFilesize']) ?>">
					<?php endif;?>
					<!-- Send the requesttoken, this is needed for older IE versions
						 because they don't send the CSRF token via HTTP header in this case -->
					<input type="hidden" name="requesttoken" value="<?php p($_['requesttoken']) ?>" id="requesttoken">
					<input type="hidden" class="max_human_file_size"
						   value="(max <?php p($_['uploadMaxHumanFilesize']); ?>)">
					<input type="hidden" name="dir" value="<?php p($_['dir']) ?>" id="dir">
					<input type="file" id="file_upload_start" name='files[]'/>
					<a href="#" class="svg"></a>
				</form>
			</div>
			<?php if ($_['trash'] ): ?>
				<div id="trash" class="button" <?php $_['trashEmpty'] ? p('disabled') : '' ?>>
					<a><?php p($l->t('Deleted files'));?></a>
				</div>
			<?php endif; ?>
			<div id="uploadprogresswrapper">
				<div id="uploadprogressbar"></div>
				<input type="button" class="stop" style="display:none"
					value="<?php p($l->t('Cancel upload'));?>"
				/>
			</div>
		</div>
		<div id="file_action_panel"></div>
		<div class="notCreatable notPublic <?php if ($_['isCreatable'] or $_['isPublic'] ):?>hidden<?php endif; ?>">
			<div class="actions"><input type="button" disabled value="<?php p($l->t('You don’t have write permissions here.'))?>"></div>
		</div>
	<input type="hidden" name="permissions" value="<?php p($_['permissions']); ?>" id="permissions">
</div>

<div id="emptycontent" <?php if (!isset($_['files']) or !$_['isCreatable'] or count($_['files']) > 0 or !$_['ajaxLoad']):?>class="hidden"<?php endif; ?>><?php p($l->t('Nothing in here. Upload something!'))?></div>

<input type="hidden" id="disableSharing" data-status="<?php p($_['disableSharing']); ?>"></input>

<table id="filestable" data-allow-public-upload="<?php p($_['publicUploadEnabled'])?>" data-preview-x="36" data-preview-y="36">
	<thead>
		<tr>
			<th id='headerName'>
				<div id="headerName-container">
					<input type="checkbox" id="select_all" />
					<label for="select_all"></label>
					<span class="name"><?php p($l->t( 'Name' )); ?></span>
					<span class="selectedActions">
						<?php if($_['allowZipDownload']) : ?>
							<a href="" class="download">
								<img class="svg" alt="Download"
									 src="<?php print_unescaped(OCP\image_path("core", "actions/download.svg")); ?>" />
								<?php p($l->t('Download'))?>
							</a>
						<?php endif; ?>
					</span>
				</div>
			</th>
			<th id="headerSize"><?php p($l->t('Size')); ?></th>
			<th id="headerDate">
				<span id="modified"><?php p($l->t( 'Modified' )); ?></span>
				<?php if ($_['permissions'] & OCP\PERMISSION_DELETE): ?>
<!--					NOTE: Temporary fix to allow unsharing of files in root of Shared folder -->
					<?php if ($_['dir'] == '/Shared'): ?>
						<span class="selectedActions"><a href="" class="delete-selected">
							<?php p($l->t('Unshare'))?>
							<img class="svg" alt="<?php p($l->t('Unshare'))?>"
								 src="<?php print_unescaped(OCP\image_path("core", "actions/delete.svg")); ?>" />
						</a></span>
					<?php else: ?>
						<span class="selectedActions"><a href="" class="delete-selected">
							<?php p($l->t('Delete'))?>
							<img class="svg" alt="<?php p($l->t('Delete'))?>"
								 src="<?php print_unescaped(OCP\image_path("core", "actions/delete.svg")); ?>" />
						</a></span>
					<?php endif; ?>
				<?php endif; ?>
			</th>
		</tr>
	</thead>
	<tbody id="fileList">
		<?php print_unescaped($_['fileList']); ?>
	</tbody>
</table>
<div id="editor"></div><!-- FIXME Do not use this div in your app! It is deprecated and will be removed in the future! -->
<div id="uploadsize-message" title="<?php p($l->t('Upload too large'))?>">
	<p>
	<?php p($l->t('The files you are trying to upload exceed the maximum size for file uploads on this server.'));?>
	</p>
</div>
<div id="scanning-message">
	<h3>
		<?php p($l->t('Files are being scanned, please wait.'));?> <span id='scan-count'></span>
	</h3>
	<p>
		<?php p($l->t('Current scanning'));?> <span id='scan-current'></span>
	</p>
</div>

<!-- config hints for javascript -->
<input type="hidden" name="ajaxLoad" id="ajaxLoad" value="<?php p($_['ajaxLoad']); ?>" />
<input type="hidden" name="allowZipDownload" id="allowZipDownload" value="<?php p($_['allowZipDownload']); ?>" />
<input type="hidden" name="usedSpacePercent" id="usedSpacePercent" value="<?php p($_['usedSpacePercent']); ?>" />
<input type="hidden" name="encryptedFiles" id="encryptedFiles" value="<?php $_['encryptedFiles'] ? p('1') : p('0'); ?>" />
