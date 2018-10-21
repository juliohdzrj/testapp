<?php
function verify_email($emailUser)
{
	if(preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/",$emailUser))
	{
		return true;
	}
	return false;
}

function verify_text($textGeneral)
{
	if(preg_match("/^[A-Za-záéíóúñÑÜü ]+$/",$textGeneral))
	{
		return true;
	}
	return false;
}
function verify_name_user($textGeneral)
{
	if(preg_match("/^([A-Za-z\d]|[^ ]){3,15}$/",$textGeneral))
	{
		return true;
	}
	return false;
}
function verify_password($textGeneral)
{
	if(preg_match("/^([A-Za-z\d]|[^ ]){8,40}$/",$textGeneral))
	{
		return true;
	}
	return false;
}