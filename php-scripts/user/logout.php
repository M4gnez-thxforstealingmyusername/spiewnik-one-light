<?php
session_start();
session_unset();
session_destroy();
echo "{'status': '1', 'message': 'wylogowano'}";
