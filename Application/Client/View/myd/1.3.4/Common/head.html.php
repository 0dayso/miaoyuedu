<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <block name="title">
	    <title><notempty name="pageTitle">{$pageTitle}-{:C('CLIENT.'.CLIENT_NAME.'.name')}
<else />{:C('CLIENT.'.CLIENT_NAME.'.name')}</notempty></title>
	</block>
    <link rel="shortcut icon" href="__IMG__/favicon/64_favicon.ico" type="image/vnd.microsoft.icon">
	<link rel="shortcut icon" href="__IMG__/favicon/64_favicon.ico" type="image/x-icon" />
    <block name="css">
		<link href="__CSS__/style.css?ver={:C('SOURCE_VER')}" rel="stylesheet" type="text/css">
    </block>
    <block name="style">

	</block>