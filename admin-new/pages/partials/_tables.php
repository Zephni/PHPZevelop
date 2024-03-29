<?php
    $Tables = DBTool::GetAllTables();
    foreach($Tables as $K => $V)
        if(substr($V["real_name"],0, 1) == "_")
            unset($Tables[$K]);
?>

<h2 class="display-4">Tables</h2>

<table id="datatable" class="table table-striped table-bordered">
    <thead class="thead-dark">
        <tr>
            <th class="w-100">Table name</th>
            <th style="min-width: 150px;">Real name</th>
            <th>Columns</th>
            <th></th>
            <th></th>
            <th>
                <a href="<?php $PHPZevelop->Path->GetPage("manage/create-table"); ?>" class="bth btn-success float-right new-button">+ New table</a>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php
            foreach($Tables as $Item)
            {
                echo "<tr>";
                echo "  <td class='align-middle font-weight-bold'>".$Item["name"]."</td>";
                echo "  <td class='align-middle'>".$Item["real_name"]."</td>";
                echo "  <td class='align-middle;'><center>".count($Item["columns"])."</center></td>";
                echo "  <td class='align-middle'><a href='".$PHPZevelop->Path->GetPage("select/".$Item["real_name"], true)."' class='btn btn-sm btn-info'>View Rows</a></td>";
                echo "  <td class='align-middle'><a href='".$PHPZevelop->Path->GetPage("manage/modify-table/".$Item["real_name"], true)."' class='btn btn-sm btn-primary'>Modify Table</a></td>";
                echo "  <td class='align-middle'><a href='".$PHPZevelop->Path->GetPage("manage/modify-table/".$Item["real_name"]."/delete", true)."' class='confirm btn btn-sm btn-danger'>Delete</a></td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('#datatable').DataTable({
            "columnDefs": [
                { "orderable": false, "targets": [3,4,5] }
            ],
            "aoColumns": [
                null,
                null,
                null,
                {"bSearchable": false },
                {"bSearchable": false },
                {"bSearchable": false }
            ]
        });
    });
</script>