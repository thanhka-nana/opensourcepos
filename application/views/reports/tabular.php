<?php $this->load->view("partial/header"); ?>

<script type="text/javascript">
	dialog_support.init("a.modal-dlg");
</script>

<div id="page_title"><?php echo $title ?></div>

<div id="page_subtitle"><?php echo $subtitle ?></div>

<div id="table_holder">
	<table id="table"></table>
</div>

<div id="report_summary">
	<?php
	foreach($summary_data as $name => $value)
	{ 
		if($name == "total_quantity")
		{
	?>
			<div class="summary_row"><?php echo $this->lang->line('reports_'.$name) . ': ' .$value; ?></div>
	<?php
		}
		else
		{
	?>
			<div class="summary_row"><?php echo $this->lang->line('reports_'.$name) . ': ' . to_currency($value); ?></div>
	<?php
		}
	}
	?>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		<?php $this->load->view('partial/bootstrap_tables_locale'); ?>

		$('#table')
			.addClass("table-striped")
			.addClass("table-bordered")
			.bootstrapTable({
				columns: <?php echo transform_headers($headers, TRUE, FALSE); ?>,
				stickyHeader: true,
				stickyHeaderOffsetLeft: $('#table').offset().left + 'px',
				stickyHeaderOffsetRight: $('#table').offset().right + 'px',
				pageSize: <?php echo $this->config->item('lines_per_page'); ?>,
				sortable: true,
				showExport: true,
				exportDataType: 'all',
				exportTypes: ['json', 'xml', 'csv', 'txt', 'sql', 'excel', 'pdf'],
				pagination: true,
				showColumns: true,
				data: <?php echo json_encode($data); ?>,
				iconSize: 'sm',
				paginationVAlign: 'bottom',
				escape: false
        });


        var make_editable = function() {
            var date = $(this).prev().prev().prev().prev().text();
            var old_total = $(this).text();

            $(this).editable('click', function(value) {
                // update row
                var $this = $(this);

                $.post("<?php echo site_url('corrections/correct_total' )?>", {"value": value.value, "date": date}, function(response) {
                    $.get("<?php echo site_url('corrections/get_summary_sales_row?date=' )?>" + encodeURIComponent(date), function(response) {

                        var row = $this.parent();
                        var index = row.data("index");
                        var table = $("#table").data('bootstrap.table');

                        table.updateRow({index: index, row: response});

                        row = $("table tr").eq(index+1);
                        // reselect..
                        //update_sortable_table();
                        $total = $("td:nth-child(5)", row);
                        var updated = $total.text() != old_total;
                        make_editable.call($total);
                       // table_support.highlight_row(id, '#e1ffdd')
                    });
                    // load summary data back in
                    $('#report_summary').load("<?php echo site_url("corrections/get_summary_totals_sales/$start_date/$end_date/0" ); ?>");
                });

            });
        };

        $("tbody td:nth-child(5)").each(make_editable);



    });
</script>

<?php $this->load->view("partial/footer"); ?>
