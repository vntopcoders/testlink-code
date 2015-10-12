{include file="inc_head.tpl" openHead='yes' jsValidate="yes" editorType=$gui->editorType}
{$deleteStepAction="lib/testcases/tcStepsTemplate.php?show_mode=$showMode&doAction=doDeleteStep&id="}
{$editStepAction="lib/testcases/tcStepsTemplate.php?doAction=doEditStep&id="}

{include file="inc_del_onclick.tpl"}
<link rel="stylesheet" type="text/css" href="{$basehref}/third_party/DataTables-1.10.4/media/css/jquery.dataTables.TestLink.css">
<script type="text/javascript" language="javascript" src="{$basehref}/third_party/DataTables-1.10.4/media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="{$basehref}/third_party/DataTables-1.10.4/media/js/jquery.dataTables.js"></script>

<script type="text/javascript" language="javascript" class="init">
$(document).ready(function() {
    $('#item_view').DataTable( {
        "lengthMenu": [[20, 25, 50, 100, -1], [20, 25, 50, 100, "All"]]
    } );
} );

var del_action=fRoot+'{$deleteStepAction}';
</script>
</head>
<body>
<div>
<table id="item_view" class="simple" style="width:100%">
  <thead>
  <tr>
    <th>ID</th>
    <th>Step</th> 
    <th></th>
    <th></th>
  </tr>
  </thead>
  {foreach from=$steps item=step}
  <tr>
    <td>{$step.id}</td>
    <td>{$step.step}</td>
    <td class="clickable_icon">
      <img style="border:none;cursor: pointer;" title="Delete step" alt="Delete step" onclick="delete_confirmation({$step.id},'{$step.id}',
                                         'Delete','<p>You are going to delete step number: %s </p><p>Are you sure?</p>');" src="gui/themes/default/images/trash.png"> 
    </td>
    <td class="clickable_icon">
    <a href="{$editStepAction}{$step.id}">Edit</a>
    </td>
  </tr>
  {/foreach}
</table>
<form method="post" name="step">
{if $stepInfo['step']}
<input type="hidden" name= 'doAction' value="doEditStep" />
<input type="hidden" name= 'id' value="{$stepInfo['id']}" />
{/if}
<table class="simple" style="width:100%">
  <tr>
    <td>
      Step <br />
      <textarea name="step" id="tc_step" rows="8" cols="80">{$stepInfo['step']}</textarea></td>
  </tr>  
  <tr>
    <td><input type="submit" name="btn_add" value="Save"></td>
  </tr>
</table>
</form>
<form name="platformsExport" id="platformsExport" method="post" action="lib/testcases/stepsExport.php">
        <input type="hidden" name="goback_url" value="{$gui.goback_url}">
      <input type="submit" name="export_steps" id="export_steps" style="margin-left: 3px;" value="Export">
               
          <input type="button" name="import_platforms" id="import_platforms" onclick="location='http://testlink.dev/lib/platforms/platformsImport.php?goback_url={$gui.goback_url}'" value="Import">
                  </form>
</div>
</body>
</html>