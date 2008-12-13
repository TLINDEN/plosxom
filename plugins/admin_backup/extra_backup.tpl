<div class="extra">
<div class="extratitle">Backup Content</div>

<p>
You can backup your content (postings, pages and images) into a
ZIP archive and use tha backup to install it into another plosxom
installation after server outage or when you move to another server.
</p>
<p>
<a href="{$config.whoami}?admin=yes&mode=admin_create_backup&back=admin_extras">Create backup now</a>
</p>
<p>
<form name="admin_restore" method="post" enctype="multipart/form-data" action="{$config.whoami}">
  <input type="hidden" name="admin" value="yes">
  <input type="hidden" name="mode" value="admin_restore_backup">
  <input type="hidden" name="back" value="admin_extras">
  Select backup zip-file: <input type="file" name="file">
  <input type="submit" name="submit" value="Restore">
</form>

{if $admin_backup_restored}
 <p>
   Content restored.
 </p>
{/if}

{if $admin_backup_created}
 <p>
   Backup of your content created.
   <a href="{$admin_backup_ziplink}">Download {$admin_backup_zipfile} here</a>
 </p>
{/if}

</p>
</div>
