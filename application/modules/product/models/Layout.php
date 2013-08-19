<?php

class Product_Model_Layout extends Rest_Model_DbRow
{

	const VIEW_PREVIEW_FRONT = 1;
	const VIEW_PREVIEW_BACK = 2;
	const VIEW_PRINT_FRONT = 3;
	const VIEW_PRINT_BACK = 4;
	
	const VIEW_PREVIEW_FRONT_SUFFIX = '';
	const VIEW_PREVIEW_BACK_SUFFIX = '_preview_back';
	const VIEW_PRINT_FRONT_SUFFIX = '_print_front';
	const VIEW_PRINT_BACK_SUFFIX = '_print_back';
}

