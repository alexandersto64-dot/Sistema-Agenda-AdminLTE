<?php

session_start();

require_once "Controllers/template.Controller.php";

$template = new ControllerTemplate();

$template->ctrTemplate();