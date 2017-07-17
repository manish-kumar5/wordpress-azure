<!DOCTYPE html>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700,700i&subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>

	<title><?php echo __( 'Kanban for WordPress', 'kanban' ); ?></title>

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<?php Kanban_Template::add_style($wp_query->query_vars['kanban']->slug); ?>
</head>
<body class="">

<h1> This is my Custom Task Manager </h1>
			<form class="navbar-form navbar-nav" id="page-search">
				<div class="form-group has-feedback">
					<input type="search" placeholder="<?php echo __( 'Search', 'kanban' ) ?>" class="form-control"
						   id="board-search">
					<span class="glyphicon glyphicon-remove form-control-feedback" id="board-search-clear"
						  style="display: none;"></span>
				</div>


				<div class="btn-group">
					<a href="#" class="btn btn-default" id="btn-filter-modal-toggle" data-toggle="modal">
						<?php echo __( 'Filter', 'kanban' ) ?>
					</a>
					<a href="#" class="btn btn-default btn-filter-reset" style="display: none;">
						<span class="glyphicon glyphicon-remove" id="board-filter-clear"></span>
					</a>
				</div><!-- btn-group -->

			</form>

<?php echo apply_filters( 'kanban_page_header_after', '' ); ?>
