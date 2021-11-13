<?php 

require_once $_SERVER['DOCUMENT_ROOT'] . '/function.php';

header('Content-Type: application/json');

if($_GET && $_GET['page']) {
	$page = $_GET['page'];
	$type = $_GET['type'];
	$date = $_GET['date'];

	$data_page = page_data($page);

	$data_page['sql']['param']['query']['param'] = $data_page['sql']['param']['query']['param'] . "  AND stock_order_report.order_my_date = :mydateyear";
	$data_page['sql']['param']['query']['bindList']['mydateyear'] = $date;
	$table_result = render_data_template($data_page['sql'], $data_page['page_data_list']);	

	$base_result = $table_result['base_result'];

	$res = $twig->render('/component/include_component.twig', [
		'renderComponent' => [
			'/component/pulgin/stats_card/stats_card_list.twig' => [
				'res' => stats_card_data($base_result, $data_page['page_data_list']['stats_card'], $date)
			]
		]
	]);

	echo json_encode([
		'report_cards' => $res
	]);
}





function stats_card_data($data, $card_list, $date) {
	$res 				= [];
	$order_count 		= 0;
	$order_turnover 	= 0;
	$order_profit 		= 0;
	$total_rasxod 		= get_total_rasxod($date);

	foreach($data as $stock) {
		$order_count 		+= $stock['order_stock_count']; 
		$order_turnover 	+= $stock['order_stock_total_price'];
		$order_profit 		+= $stock['order_total_profit'];
	}

	foreach($card_list as $type) {
		//количество товара
		switch ($type) {
			case 'order_turnover':
				array_push($res, [
					'title' => 'Ümumi dövriyyə',
					'fields' => $type,
					'value' => decorate_num($order_turnover),
					'mark' 	=> [
						'mark_text' => '',
						'mark_modify_class' => 'mark-large-icon-manat button-icon-right manat-icon--black'
					],
					'icon' => '<svg width="46" height="46" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M8.06519 23C8.06519 31.2351 14.7649 37.9349 22.9999 37.9349C31.235 37.9349 37.9347 31.2351 37.9347 23C37.9347 14.765 31.235 8.06512 22.9999 8.06512C14.7649 8.06512 8.06519 14.765 8.06519 23ZM35.8106 23C35.8106 30.064 30.0637 35.8108 22.9999 35.8108C15.9361 35.8108 10.1893 30.064 10.1893 23C10.1893 15.9361 15.9361 10.1892 22.9999 10.1892C30.0637 10.1892 35.8106 15.9361 35.8106 23Z" fill="white"/>
									<path d="M22.0444 14.6317V16.2953C21.4467 16.4334 20.8827 16.693 20.3635 17.0763C18.9431 18.1246 18.3047 19.5774 18.6558 20.9624C18.9935 22.2944 20.2052 23.2624 21.8183 23.4886C25.4756 24.001 26.0709 24.5472 25.9143 25.8282C25.8309 26.5107 25.4386 26.9994 24.7483 27.28C23.5813 27.7546 21.5581 27.5533 19.6678 26.0342L18.3373 27.6901C19.4802 28.6085 20.7715 29.2166 22.0441 29.4818V31.368H24.1682V29.5934C24.6501 29.5376 25.114 29.424 25.5482 29.2474C26.9447 28.6797 27.8466 27.5273 28.0228 26.0855C28.2227 24.4491 27.5808 23.1828 26.1666 22.4237C25.0706 21.8353 23.6238 21.5964 22.1131 21.3847C21.3606 21.2793 20.8379 20.9262 20.7147 20.4402C20.5832 19.9215 20.9235 19.3028 21.6248 18.7851C22.3676 18.2371 23.2428 18.1563 24.3006 18.5381C25.1982 18.8623 25.8595 19.4046 25.866 19.41L26.5461 18.5943L27.2292 17.7811C27.1378 17.7042 26.2999 17.0168 25.092 16.5659C24.7798 16.4494 24.4719 16.3605 24.1684 16.2965V14.6316L22.0444 14.6317Z" fill="white"/>
									<path d="M23 2.12411C27.5683 2.12411 31.9138 3.58366 35.5039 6.27986H32.7109V8.40397H39.5455V1.56929H37.4214V5.08101C33.3468 1.78963 28.3098 0 23 0C10.3178 0 0 10.3178 0 23H2.12411C2.12411 11.489 11.489 2.12411 23 2.12411Z" fill="white"/>
									<path d="M43.8759 23C43.8759 34.511 34.511 43.8759 23.0001 43.8759C18.5614 43.8759 14.3208 42.4928 10.7856 39.9335H13.5587V37.8094H6.724V44.6441H8.84811V41.1318C12.8763 44.2858 17.8152 46 23.0001 46C35.6824 46 46.0001 35.6823 46.0001 23H43.8759Z" fill="white"/>
								</svg>'
				]);
			break;
			case 'order_profit':
				array_push($res, [
					'title' => 'Mənfəət',
					'fields' => $type,
					'value' => decorate_num($order_profit - $total_rasxod),
					'mark' 	=> [
						'mark_text' => '',
						'mark_modify_class' => 'mark-large-icon-manat button-icon-right manat-icon--black'
					],
					'icon' => '<svg width="508" height="512" viewBox="0 0 508 512" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M507.191 154.526L506.185 149.27C505.046 143.316 501.451 138.049 496.321 134.818C491.193 131.588 484.889 130.622 479.029 132.166L456.434 138.117C445.318 141.045 438.302 152.184 440.462 163.476L440.868 165.589C442.861 176.006 452.064 183.419 462.439 183.419C463.309 183.419 464.189 183.367 465.073 183.259L488.268 180.448C494.285 179.718 499.786 176.493 503.36 171.597C506.934 166.702 508.331 160.479 507.191 154.526ZM491.234 162.745C490.087 164.315 488.392 165.309 486.462 165.543L463.266 168.354C459.661 168.788 456.296 166.337 455.613 162.767C455.613 162.766 455.613 162.766 455.613 162.765L455.207 160.651C454.524 157.081 456.743 153.559 460.258 152.633L482.852 146.682C483.446 146.526 484.045 146.449 484.641 146.449C485.93 146.449 487.194 146.812 488.32 147.52C489.966 148.557 491.074 150.18 491.439 152.09L492.445 157.346C492.811 159.258 492.381 161.174 491.234 162.745Z" fill="black"/>
									<path d="M460.35 93.5835C465.196 89.9433 468.347 84.3995 468.994 78.3732C469.641 72.347 467.74 66.2587 463.777 61.6727L460.277 57.6231C456.313 53.0371 450.566 50.2737 444.509 50.0405C438.448 49.7993 432.509 52.1213 428.206 56.3891L411.614 72.8404C403.451 80.9344 402.945 94.0879 410.462 102.787L411.871 104.417L411.87 104.416C416.18 109.404 422.322 111.993 428.511 111.993C433.115 111.993 437.746 110.56 441.665 107.616L460.35 93.5835ZM423.23 94.5993C423.229 94.5993 423.229 94.5993 423.23 94.5993L421.822 92.9699C419.445 90.2195 419.605 86.0599 422.186 83.5007L438.778 67.0493C440.093 65.7452 441.817 65.0366 443.656 65.0366C443.748 65.0366 443.84 65.0386 443.932 65.0416C445.876 65.1167 447.647 65.9684 448.918 67.4397L452.417 71.4892C453.689 72.9605 454.274 74.8361 454.066 76.7698C453.859 78.7035 452.888 80.411 451.334 81.579L432.651 95.6112C429.743 97.7941 425.606 97.3497 423.23 94.5993V94.5993Z" fill="black"/>
									<path d="M354.139 65.2308L356.174 65.9374C358.534 66.7571 360.949 67.1474 363.331 67.1474C371.908 67.1474 380.049 62.085 383.577 53.8088L392.741 32.3161C395.118 26.7402 395.075 20.3627 392.621 14.8199C390.168 9.27702 385.478 4.95726 379.752 2.96652L374.697 1.20999C368.973 -0.779739 362.614 -0.300321 357.251 2.52514C351.889 5.3516 347.899 10.3269 346.304 16.176L340.162 38.7187C337.141 49.8103 343.279 61.4555 354.139 65.2308ZM354.649 42.6661L360.791 20.1235C361.303 18.2468 362.532 16.7135 364.252 15.8077C365.269 15.2712 366.369 15.001 367.478 15.001C368.246 15.001 369.017 15.1301 369.769 15.3923L374.823 17.1489C376.66 17.7874 378.105 19.1176 378.892 20.8961C379.68 22.6747 379.693 24.6394 378.93 26.429L369.766 47.9217C368.34 51.2656 364.536 52.9511 361.101 51.757C361.101 51.757 361.1 51.757 361.099 51.756L359.067 51.0504C355.634 49.8554 353.693 46.1742 354.649 42.6661V42.6661Z" fill="black"/>
									<path d="M381.788 158.084C379.4 157.897 377.008 157.715 374.617 157.533C339.507 113.309 298.776 73.9583 253.27 40.3651C242.609 32.4902 227.65 33.1518 217.697 41.9385C195.835 61.2513 174.117 81.0916 153.145 100.91C150.133 103.757 149.997 108.508 152.845 111.522C155.692 114.536 160.444 114.67 163.457 111.822C184.307 92.1192 205.9 72.392 227.636 53.1923C232.317 49.0617 239.346 48.7464 244.353 52.4426C294.706 89.615 339.086 133.994 376.26 184.35C379.954 189.353 379.636 196.376 375.5 201.053C320.117 263.765 261.046 324.455 199.88 381.518C197.144 380.399 194.374 379.196 191.577 377.913C214.528 356.572 237.189 334.732 259.049 312.871C286.996 284.925 314.717 255.898 341.442 226.597C342.801 225.107 343.504 223.133 343.391 221.12C343.278 219.106 342.361 217.222 340.844 215.893C340.402 215.506 340.042 215.167 339.658 214.777C334.395 209.518 331.497 202.524 331.497 195.084C331.497 187.642 334.395 180.645 339.659 175.382C341.2 173.84 342.94 172.471 344.834 171.309C346.693 170.168 347.964 168.277 348.316 166.124C348.668 163.972 348.067 161.772 346.669 160.098C323.099 131.882 296.833 105.613 268.6 82.0194C266.926 80.6201 264.726 80.0196 262.574 80.3709C260.423 80.7232 258.529 81.9943 257.388 83.853C256.225 85.7496 254.851 87.4941 253.303 89.0415C248.045 94.3041 241.051 97.2026 233.61 97.2026C226.169 97.2026 219.173 94.3041 213.908 89.0405C213.489 88.6211 213.094 88.1847 212.698 87.7494C211.357 86.2761 209.485 85.3973 207.495 85.3052C205.507 85.2102 203.561 85.9158 202.09 87.2589C172.688 114.085 143.665 141.802 115.827 169.642C90.8118 194.655 65.8661 220.683 41.5559 247.1C41.0785 246.362 40.6081 245.627 40.1527 244.902C39.0598 243.166 38.0169 241.448 37.025 239.76C70.7874 203.15 106.042 167.049 141.908 132.401C144.89 129.521 144.972 124.769 142.092 121.787C139.211 118.805 134.46 118.722 131.477 121.604C120.214 132.484 109.021 143.521 97.902 154.657C80.7751 155.582 63.455 156.729 46.3651 158.083C26.1906 159.673 9.95741 175.473 7.7655 195.653C-2.02502 285.953 -2.02502 377.529 7.7655 467.839C9.95741 488.019 26.1906 503.819 46.3651 505.409C102.041 509.802 158.135 512 214.183 511.999C258.682 511.999 303.155 510.613 347.337 507.839C351.475 507.579 354.619 504.015 354.359 499.877C354.1 495.739 350.529 492.61 346.397 492.855C247.269 499.078 146.722 498.266 47.5462 490.442C34.6049 489.422 24.1868 479.302 22.7135 466.359C22.7135 466.358 22.7135 466.358 22.7135 466.357C20.7318 443.721 45.8617 402.645 86.7373 361.709C89.6669 358.775 89.6628 354.022 86.7293 351.094C83.7957 348.164 79.0426 348.168 76.114 351.102C49.9312 377.324 30.3522 402.965 18.9673 425.287C14.2942 363.021 14.2972 300.341 18.9763 238.079C19.6899 239.469 20.4235 240.869 21.2032 242.29C23.0628 245.697 25.1637 249.264 27.4477 252.895C29.5945 256.308 31.9666 259.873 34.5028 263.498C42.3006 274.611 51.5567 286.082 61.9927 297.571C62.6653 298.319 63.3499 299.06 64.0415 299.81L64.62 300.437C81.4517 318.62 100.843 336.311 120.701 351.598C139.022 365.687 157.289 377.444 174.994 386.542C183.098 390.705 191.127 394.311 198.847 397.257C203.217 398.931 207.618 400.435 211.928 401.727C212.631 401.937 213.357 402.042 214.082 402.042C214.806 402.042 215.529 401.938 216.23 401.728C244.613 393.25 276.715 375.484 309.064 350.349C316.556 344.531 324.016 338.344 331.236 331.96C343.749 320.901 355.595 309.232 366.443 297.272C385.544 276.203 400.03 256.016 409.189 238.073C413.873 300.334 413.876 363.014 409.199 425.28C397.814 402.96 378.234 377.32 352.053 351.1C349.124 348.165 344.371 348.162 341.438 351.092C338.505 354.021 338.501 358.773 341.43 361.707C382.277 402.614 407.4 443.66 405.457 466.307C404.014 479.275 393.583 489.418 380.598 490.442C379.135 490.561 377.671 490.67 376.206 490.778C372.072 491.086 368.969 494.686 369.276 498.82C369.569 502.766 372.862 505.772 376.755 505.772C376.941 505.772 377.129 505.765 377.318 505.75C378.817 505.639 380.315 505.527 381.796 505.407C401.983 503.817 418.217 488.014 420.397 467.836C430.197 377.528 430.197 285.951 420.397 195.654C418.216 175.478 401.982 159.675 381.788 158.084V158.084ZM47.5472 173.051C59.0051 172.143 70.5652 171.337 82.1023 170.619C64.3098 188.745 46.7825 207.126 29.6255 225.652C24.45 214.322 22.065 204.592 22.7055 197.17C24.1648 184.207 34.5909 174.072 47.5472 173.051V173.051ZM115.587 328.223C110.112 323.625 104.721 318.868 99.4574 314.001C99.4754 313.983 99.4904 313.962 99.5084 313.944C103.713 309.746 110.55 309.743 114.75 313.941C116.787 315.979 117.909 318.686 117.909 321.563C117.908 324.007 117.092 326.321 115.587 328.223ZM127.258 337.68C130.926 333.122 132.922 327.498 132.922 321.564C132.922 314.676 130.237 308.199 125.364 303.326C115.316 293.277 98.9579 293.275 88.8952 303.326C88.7951 303.426 88.703 303.531 88.6039 303.633C84.145 299.225 79.8143 294.756 75.6486 290.255L75.0741 289.631C74.4236 288.927 73.78 288.229 73.1244 287.502C64.62 278.14 56.9714 268.833 50.3096 259.765C75.2022 232.644 100.787 205.915 126.443 180.259C152.505 154.198 179.607 128.246 207.089 103.036C214.62 108.989 223.868 112.218 233.612 112.218C245.064 112.218 255.83 107.757 263.924 99.6577C264.271 99.3114 264.611 98.9581 264.947 98.5988C288.25 118.595 310.115 140.463 330.093 163.754C329.737 164.086 329.387 164.424 329.043 164.768C320.944 172.868 316.483 183.635 316.483 195.087C316.483 204.845 319.722 214.104 325.661 221.608C300.543 248.998 274.588 276.102 248.433 302.258C225.39 325.3 201.451 348.318 177.222 370.749C161.765 362.43 145.855 352.008 129.856 339.705C128.987 339.034 128.122 338.357 127.258 337.68V337.68ZM355.322 287.189C344.845 298.74 333.396 310.019 321.294 320.713C314.31 326.888 307.097 332.87 299.854 338.495C271.112 360.826 242.805 376.981 217.566 385.53C276.434 330.175 333.299 271.516 386.748 210.995C395.541 201.049 396.209 186.092 388.335 175.431C388.126 175.146 387.909 174.868 387.698 174.585C397.23 178.041 404.293 186.691 405.458 197.197C407.117 216.767 387.917 251.236 355.322 287.189V287.189Z" fill="black"/>
									<path d="M240.016 293.837C255.32 278.533 264.331 258.716 265.388 238.038C266.46 217.07 259.259 197.731 245.111 183.583C216.119 154.591 166.659 156.877 134.857 188.678C119.553 203.983 110.543 223.799 109.485 244.477C108.413 265.445 115.614 284.784 129.763 298.932C143.381 312.55 161.509 319.266 180.208 319.266C201.317 319.266 223.152 310.701 240.016 293.837ZM124.479 245.244C125.347 228.284 132.802 211.965 145.473 199.294C171.421 173.346 211.357 171.06 234.496 194.198C245.602 205.304 251.248 220.6 250.395 237.271C249.528 254.23 242.072 270.549 229.401 283.221C215.45 297.171 197.464 304.281 180.213 304.28C165.374 304.279 151.078 299.015 140.379 288.316C129.273 277.211 123.626 261.914 124.479 245.244V245.244Z" fill="black"/>
									<path d="M267.742 186.728C274.346 186.728 280.95 184.214 285.977 179.187C296.03 169.133 296.03 152.773 285.977 142.718C275.922 132.666 259.563 132.666 249.508 142.718C239.453 152.772 239.453 169.132 249.508 179.187C254.534 184.214 261.138 186.728 267.742 186.728ZM260.123 153.336C262.224 151.235 264.983 150.185 267.742 150.185C270.5 150.185 273.26 151.235 275.36 153.335C279.56 157.535 279.56 164.37 275.36 168.572C271.159 172.772 264.324 172.772 260.122 168.572C255.921 164.371 255.923 157.535 260.123 153.336V153.336Z" fill="black"/>
									<path d="M207.727 242.31C210.48 245.959 210.244 253.034 204.381 258.963C198.757 264.648 196.559 266.353 189.907 268.368C186.309 269.458 183.865 272.966 184.42 276.684C184.998 280.55 188.273 283.129 191.851 283.129C192.565 283.129 193.29 283.025 194.009 282.81C201.144 280.671 205.363 278.289 209.569 274.708L211.063 276.202C212.528 277.667 214.45 278.4 216.37 278.4C218.234 278.4 220.097 277.711 221.544 276.333C224.608 273.414 224.442 268.352 221.451 265.359L219.754 263.663C226.649 252.835 225.369 240.767 219.707 233.265C212.811 224.126 200.869 222.331 189.283 228.691C182.537 232.395 174.913 236.339 169.523 237.498C167.906 237.844 166.826 236.88 166.205 236.01C164.949 234.252 164.366 231.116 166.494 227.585C169.085 223.284 172.251 220.795 174.809 219.375C177.526 217.866 179.087 214.889 178.642 211.812L178.609 211.581C177.876 206.496 172.303 203.701 167.787 206.153C166.684 206.751 165.533 207.451 164.362 208.267L163.33 207.235C160.425 204.329 155.65 204.035 152.658 206.852C149.561 209.769 149.506 214.643 152.492 217.63L154.046 219.184C153.909 219.402 153.769 219.614 153.634 219.838C148.789 227.879 148.929 237.653 153.988 244.736C158.365 250.863 165.525 253.711 172.681 252.176C178.549 250.913 185.452 247.922 196.51 241.852C199.381 240.276 204.656 238.239 207.727 242.31V242.31Z" fill="black"/>
								</svg>'
					
				]);
			break;
			case 'rasxod':
				array_push($res, [
					'title' => 'Расходы',
					'fields' => $type,
					'value' => decorate_num($total_rasxod),
					'mark' 	=> [
						'mark_text' => '',
						'mark_modify_class' => 'mark-large-icon-manat button-icon-right manat-icon--black'
					],
					'icon' => '<svg width="482" height="484" viewBox="0 0 482 484" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M39.8516 346.097C41.5627 346.559 43.3272 346.794 45.0996 346.796C48.6188 346.787 52.074 345.854 55.1191 344.09C58.1642 342.326 60.6925 339.793 62.4506 336.744C64.2087 333.696 65.1351 330.239 65.1369 326.719C65.1387 323.2 64.2159 319.742 62.4609 316.692C60.8134 313.831 58.4858 311.421 55.6843 309.675C52.8828 307.929 49.6939 306.9 46.4 306.681C43.1061 306.461 39.809 307.058 36.8007 308.417C33.7923 309.776 31.1657 311.857 29.1534 314.474C27.141 317.091 25.8052 320.163 25.2641 323.42C24.7231 326.676 24.9935 330.016 26.0515 333.143C27.1095 336.27 28.9223 339.088 31.3295 341.347C33.7366 343.606 36.6638 345.237 39.8516 346.094V346.097ZM37.3172 324.629C37.7996 322.836 38.8888 321.266 40.3994 320.185C41.9099 319.105 43.7482 318.582 45.6012 318.706C47.4541 318.829 49.2069 319.591 50.5609 320.862C51.915 322.133 52.7865 323.834 53.0269 325.675C53.2673 327.516 52.8618 329.384 51.8794 330.96C50.8971 332.536 49.3986 333.723 47.6395 334.317C45.8804 334.912 43.9694 334.879 42.2321 334.223C40.4949 333.567 39.0389 332.328 38.1123 330.719C37.5837 329.807 37.2412 328.799 37.1047 327.754C36.9682 326.709 37.0404 325.647 37.3172 324.629V324.629Z" fill="black"/>
									<path d="M35.8249 369.633C38.843 370.249 41.9154 370.559 44.9957 370.559C55.1247 370.543 64.9753 367.242 73.0684 361.151C83.1337 353.596 87.8261 343.603 89.2536 326.667L89.2672 326.466C89.3485 324.84 89.6 319.892 96.8237 316.127C100.437 314.314 104.44 313.415 108.482 313.51L129.784 313.811C133.036 313.956 136.23 314.716 139.199 316.05C157.865 343.253 181.621 375.081 206.997 402.089C211.892 407.244 217.778 411.358 224.302 414.183C230.826 417.009 237.853 418.488 244.962 418.532C249.926 418.514 254.865 417.844 259.655 416.541C260.596 416.288 261.463 415.811 262.179 415.15C262.896 414.489 263.441 413.664 263.769 412.746C264.096 411.828 264.196 410.844 264.059 409.878C263.923 408.913 263.554 407.996 262.984 407.204L233.346 366.071C241.066 331.501 252.169 303.863 266.391 283.939C268.397 281.124 271.045 278.828 274.115 277.241C277.186 275.654 280.591 274.821 284.047 274.812H398.405C400.581 274.814 402.666 275.679 404.205 277.218C405.743 278.756 406.608 280.842 406.611 283.017V364.353C406.611 364.49 406.611 364.629 406.626 364.766C406.819 370.668 409.003 376.331 412.823 380.835H362.749C370.881 344.608 383.312 318.43 394.638 300.546C395.073 299.877 395.372 299.129 395.516 298.345C395.66 297.56 395.647 296.755 395.477 295.975C395.308 295.196 394.985 294.458 394.528 293.804C394.071 293.151 393.488 292.594 392.814 292.167C392.14 291.741 391.389 291.452 390.602 291.318C389.816 291.184 389.011 291.208 388.233 291.388C387.456 291.568 386.723 291.9 386.075 292.366C385.427 292.831 384.879 293.421 384.461 294.101C364.884 325.009 342.112 379.356 341.804 465.107L332.88 457.969C331.811 457.114 330.484 456.648 329.115 456.648C327.747 456.648 326.419 457.114 325.35 457.969L310.292 470.016L295.242 457.969C294.173 457.114 292.846 456.648 291.477 456.648C290.109 456.648 288.781 457.114 287.712 457.969L272.654 470.016L257.607 457.969C256.538 457.114 255.211 456.648 253.842 456.648C252.473 456.648 251.146 457.114 250.077 457.969L235.035 470.016L222.274 459.803C222.356 455.061 222.654 444.719 223.755 431.082C223.878 429.492 223.366 427.918 222.332 426.705C221.297 425.492 219.823 424.739 218.234 424.611C216.645 424.483 215.07 424.99 213.853 426.021C212.637 427.052 211.88 428.523 211.747 430.112C210.184 449.493 210.194 462.158 210.196 462.689C210.198 463.589 210.402 464.478 210.793 465.289C211.184 466.1 211.751 466.814 212.454 467.376L231.269 482.435C232.337 483.291 233.665 483.757 235.034 483.757C236.402 483.757 237.73 483.291 238.798 482.435L253.841 470.388L268.887 482.435C269.956 483.291 271.283 483.757 272.652 483.757C274.021 483.757 275.348 483.291 276.417 482.435L291.476 470.388L306.524 482.435C307.592 483.291 308.92 483.757 310.289 483.757C311.657 483.757 312.985 483.291 314.053 482.435L329.112 470.388L344.171 482.435C345.066 483.152 346.146 483.599 347.286 483.723C348.426 483.846 349.577 483.643 350.605 483.135C351.633 482.627 352.494 481.837 353.089 480.856C353.683 479.876 353.985 478.746 353.959 477.6C353.155 449.218 355.275 420.829 360.284 392.882H412.636C425.629 392.882 432.514 388.468 434.572 386.882C459.269 372.17 474.35 330.662 479.393 263.474C481.59 231.689 481.736 199.795 479.828 167.992C479.749 166.89 479.368 165.832 478.727 164.932C478.087 164.032 477.211 163.326 476.196 162.89L462.161 156.866C461.411 156.544 460.602 156.378 459.785 156.378C458.968 156.378 458.16 156.544 457.409 156.866L445.753 161.87L434.104 156.868C433.353 156.546 432.544 156.379 431.727 156.379C430.91 156.379 430.102 156.546 429.351 156.868L417.697 161.872L406.049 156.869C405.298 156.547 404.489 156.381 403.672 156.381C402.855 156.381 402.046 156.547 401.295 156.869L389.652 161.872L378.01 156.869C377.075 156.468 376.054 156.31 375.042 156.409C374.03 156.509 373.06 156.863 372.221 157.439C371.383 158.015 370.704 158.793 370.248 159.702C369.792 160.611 369.573 161.621 369.613 162.637C370.32 189.51 369.54 216.402 367.277 243.19C367.139 244.781 367.64 246.363 368.668 247.586C369.696 248.809 371.168 249.573 372.759 249.711C374.351 249.849 375.932 249.348 377.155 248.32C378.378 247.292 379.143 245.82 379.281 244.229C381.943 213.477 382.042 185.618 381.86 171.635L387.274 173.961C388.025 174.284 388.834 174.45 389.652 174.45C390.469 174.45 391.278 174.284 392.029 173.961L403.673 168.96L415.321 173.961C416.072 174.284 416.881 174.45 417.698 174.45C418.515 174.45 419.324 174.284 420.075 173.961L431.727 168.959L443.375 173.961C444.126 174.284 444.935 174.45 445.752 174.45C446.569 174.45 447.377 174.284 448.128 173.961L459.787 168.957L468.06 172.508C471.305 223.923 470.168 349.637 429.393 375.914C425.696 375.84 419.454 374.603 418.659 364.135V283.013C418.653 277.643 416.518 272.495 412.721 268.699C408.924 264.902 403.776 262.766 398.407 262.76H284.049C278.674 262.771 273.379 264.063 268.603 266.528C263.827 268.993 259.708 272.561 256.586 276.936C252.819 282.254 249.384 287.799 246.299 293.539C218.657 288.488 190.768 284.892 162.748 282.766C162.702 282.766 162.658 282.766 162.613 282.766C158.893 281.701 154.272 279.079 150.239 272.919L139.353 254.615C137.244 251.164 136.021 247.245 135.793 243.208C135.45 235.068 139.612 232.382 140.981 231.498L141.148 231.387C155.115 221.69 161.434 212.638 162.956 200.145C163.768 193.612 163.186 186.981 161.249 180.689C159.312 174.397 156.064 168.587 151.718 163.641C147.676 158.992 142.682 155.266 137.074 152.716C131.467 150.166 125.376 148.851 119.216 148.86H119.139C112.175 148.86 105.31 150.505 99.1019 153.661C92.8942 156.818 87.5197 161.396 83.4165 167.023C79.3132 172.65 76.5971 179.166 75.4894 186.042C74.3818 192.917 74.9139 199.957 77.0424 206.588C77.5966 208.56 82.7859 226.056 99.8987 254.223C100.478 255.234 106.166 265.111 115.56 280.058C99.7481 279.504 89.5202 279.513 88.454 279.519C55.4752 278.745 37.7479 282.983 35.7662 283.49C28.9525 284.957 22.5822 288.015 17.175 292.413C11.7678 296.811 7.47696 302.425 4.65245 308.797C1.82794 315.169 0.549809 322.118 0.922337 329.078C1.29486 336.038 3.30749 342.811 6.79603 348.845C9.86255 354.188 14.0416 358.809 19.0501 362.396C24.0585 365.982 29.7793 368.45 35.8249 369.633V369.633ZM240.682 304.744C237.67 311.27 234.866 318.234 232.269 325.638L226.208 324.605C225.428 324.472 224.629 324.494 223.858 324.67C223.087 324.846 222.358 325.172 221.712 325.629C221.067 326.087 220.518 326.667 220.097 327.337C219.676 328.007 219.391 328.753 219.258 329.532C219.125 330.312 219.147 331.111 219.323 331.882C219.499 332.653 219.825 333.382 220.282 334.028C220.74 334.673 221.32 335.222 221.99 335.643C222.659 336.064 223.405 336.349 224.185 336.482L228.472 337.212C226.903 342.344 225.423 347.653 224.032 353.137L183.406 296.755C203.853 298.858 223.02 301.532 240.682 304.744ZM110.315 248.182C110.288 248.132 110.258 248.082 110.229 248.031C93.3963 220.344 88.6633 203.449 88.6212 203.29C88.594 203.192 88.5654 203.094 88.5338 202.999C86.9679 198.172 86.5664 193.042 87.3622 188.03C88.1579 183.018 90.1284 178.265 93.1124 174.16C96.0964 170.055 100.009 166.714 104.532 164.411C109.054 162.107 114.057 160.907 119.132 160.907H119.188C123.642 160.905 128.045 161.86 132.098 163.708C136.152 165.557 139.76 168.255 142.679 171.62C145.897 175.289 148.301 179.598 149.733 184.264C151.165 188.929 151.592 193.845 150.986 198.688C150.069 206.217 146.959 212.661 134.341 221.437C126.993 226.224 123.33 233.927 123.746 243.715C124.057 249.748 125.858 255.609 128.988 260.775L139.955 279.195C139.996 279.263 140.038 279.331 140.082 279.397C147.396 290.654 156.574 294.186 162.985 295.168C164.619 295.45 166.181 296.052 167.581 296.939C168.981 297.827 170.192 298.983 171.144 300.341L247.557 406.393C241.728 406.734 235.895 405.814 230.455 403.696C225.015 401.577 220.096 398.31 216.034 394.117C215.974 394.049 215.915 393.983 215.853 393.918C190.527 366.976 166.808 335.088 148.293 308.018C148.187 307.845 148.072 307.677 147.948 307.515C125.336 274.416 110.581 248.648 110.315 248.182V248.182ZM13.9987 318.384C15.5362 312.653 18.6286 307.459 22.9339 303.376C27.2391 299.292 32.5896 296.479 38.394 295.246C38.4858 295.227 38.5867 295.203 38.6786 295.179C38.8472 295.135 55.8622 290.803 88.2462 291.565H88.4179C88.5685 291.565 102.325 291.51 123.445 292.42C125.387 295.417 127.427 298.529 129.563 301.756L108.654 301.455C102.613 301.307 96.6317 302.672 91.2534 305.426C82.5645 309.956 77.7201 316.975 77.2367 325.729C75.9311 341.043 71.9029 346.951 65.8326 351.509C61.939 354.452 57.466 356.535 52.7084 357.623C47.9509 358.711 43.0168 358.778 38.2313 357.82C33.8597 356.97 29.7219 355.19 26.0978 352.602C22.4738 350.014 19.4481 346.677 17.2258 342.817C15.0976 339.161 13.7166 335.119 13.1626 330.924C12.6086 326.73 12.8928 322.468 13.9987 318.384V318.384Z" fill="black"/>
									<path d="M255.575 212.914C261.831 212.912 268.074 212.361 274.233 211.268L298.534 238.524C299.317 239.403 300.34 240.032 301.476 240.336C302.613 240.639 303.814 240.603 304.931 240.232C306.047 239.861 307.03 239.171 307.759 238.247C308.488 237.323 308.93 236.206 309.031 235.034L312.355 196.47C334.006 182.789 349.991 161.753 357.371 137.228C364.751 112.703 363.03 86.3387 352.524 62.9816C342.018 39.6244 323.434 20.8447 300.188 10.0952C276.942 -0.654336 250.597 -2.65111 225.996 4.47197C201.396 11.5951 180.193 27.3592 166.287 48.8661C152.381 70.373 146.706 96.177 150.305 121.534C153.904 146.891 166.536 170.096 185.878 186.884C205.22 203.671 229.972 212.912 255.583 212.908L255.575 212.914ZM255.575 12.2972C276.249 12.2964 296.349 19.0903 312.782 31.6333C329.216 44.1762 341.071 61.7728 346.523 81.7141C351.975 101.655 350.722 122.836 342.956 141.995C335.191 161.154 321.343 177.23 303.545 187.748C302.72 188.236 302.024 188.915 301.514 189.727C301.004 190.539 300.695 191.461 300.613 192.416L298.223 220.083L280.946 200.694C280.24 199.901 279.337 199.309 278.328 198.978C277.319 198.647 276.24 198.588 275.201 198.808C262.277 201.558 248.918 201.561 235.992 198.816C223.066 196.071 210.862 190.64 200.17 182.875C189.478 175.109 180.538 165.184 173.93 153.741C167.321 142.298 163.191 129.594 161.808 116.452C160.425 103.311 161.82 90.0253 165.902 77.4575C169.985 64.8897 176.663 53.3203 185.504 43.4996C194.345 33.679 205.152 25.8263 217.223 20.451C229.295 15.0756 242.361 12.2977 255.575 12.2972V12.2972Z" fill="black"/>
									<path d="M139.202 193.072C139.203 189.102 138.026 185.221 135.82 181.92C133.614 178.619 130.48 176.045 126.812 174.526C123.144 173.006 119.108 172.609 115.214 173.383C111.32 174.157 107.743 176.069 104.935 178.876C102.128 181.684 100.216 185.26 99.4412 189.154C98.6666 193.048 99.064 197.084 100.583 200.752C102.103 204.42 104.675 207.555 107.976 209.761C111.278 211.967 115.159 213.144 119.129 213.144C124.451 213.138 129.553 211.022 133.316 207.259C137.079 203.496 139.196 198.394 139.202 193.072ZM119.129 201.097C117.541 201.097 115.989 200.626 114.669 199.744C113.35 198.862 112.321 197.609 111.713 196.142C111.106 194.675 110.947 193.061 111.257 191.504C111.567 189.947 112.331 188.517 113.454 187.395C114.577 186.272 116.007 185.508 117.564 185.199C119.121 184.889 120.735 185.048 122.201 185.656C123.668 186.264 124.921 187.293 125.803 188.613C126.685 189.933 127.155 191.485 127.155 193.072C127.153 195.2 126.307 197.239 124.803 198.744C123.299 200.248 121.259 201.094 119.132 201.097H119.129Z" fill="black"/>
									<path d="M255.881 114.301C256.279 114.325 257.378 114.352 258.473 114.378C259.36 114.398 260.242 114.416 260.57 114.434C263.177 114.598 265.633 115.717 267.467 117.577C269.302 119.436 270.386 121.907 270.514 124.516C270.642 127.125 269.805 129.69 268.161 131.721C266.518 133.751 264.183 135.105 261.605 135.524C260.738 135.657 259.863 135.722 258.986 135.718C258.885 135.718 258.787 135.718 258.685 135.718C256.682 135.773 254.71 135.222 253.027 134.135C251.344 133.049 250.029 131.479 249.255 129.631C248.704 128.141 247.587 126.929 246.146 126.258C244.705 125.588 243.058 125.514 241.563 126.053C240.068 126.592 238.847 127.699 238.165 129.134C237.483 130.569 237.396 132.216 237.922 133.715C239.139 136.905 241.089 139.765 243.614 142.064C246.139 144.363 249.168 146.036 252.458 146.95V152.585C252.458 154.183 253.093 155.715 254.222 156.845C255.352 157.974 256.884 158.609 258.482 158.609C260.079 158.609 261.611 157.974 262.741 156.845C263.871 155.715 264.505 154.183 264.505 152.585V147.224C269.892 146.119 274.693 143.094 278.014 138.711C281.334 134.328 282.948 128.887 282.554 123.402C282.16 117.918 279.785 112.763 275.872 108.9C271.959 105.037 266.775 102.728 261.286 102.405C260.894 102.382 259.822 102.355 258.744 102.331C257.848 102.311 256.946 102.293 256.605 102.274C253.998 102.11 251.542 100.992 249.707 99.1321C247.873 97.2724 246.787 94.8019 246.659 92.1926C246.531 89.5833 247.368 87.0182 249.012 84.9874C250.655 82.9566 252.989 81.6025 255.568 81.1838C256.436 81.0509 257.314 80.986 258.193 80.9896H258.494C260.485 80.933 262.447 81.4773 264.124 82.5517C265.801 83.6262 267.115 85.1808 267.896 87.0131C268.429 88.5194 269.538 89.7524 270.98 90.4409C272.421 91.1294 274.078 91.217 275.584 90.6845C277.09 90.1519 278.323 89.0428 279.012 87.6011C279.7 86.1594 279.788 84.5032 279.255 82.9969C278.024 79.7748 276.047 76.8907 273.485 74.5813C270.923 72.2719 267.849 70.6031 264.517 69.712V64.1252C264.517 62.5277 263.883 60.9956 262.753 59.8659C261.623 58.7363 260.091 58.1017 258.494 58.1017C256.896 58.1017 255.364 58.7363 254.234 59.8659C253.105 60.9956 252.47 62.5277 252.47 64.1252V69.5253C247.12 70.6841 242.371 73.7401 239.099 78.1285C235.828 82.5169 234.255 87.9412 234.672 93.399C235.088 98.8569 237.466 103.98 241.366 107.82C245.266 111.661 250.425 113.961 255.888 114.294L255.881 114.301Z" fill="black"/>
									<path d="M161.021 314.367C161.415 314.369 161.808 314.334 162.196 314.26C162.97 314.101 163.707 313.795 164.366 313.356C164.694 313.135 165.001 312.883 165.283 312.603C166.41 311.474 167.043 309.944 167.043 308.348C167.043 306.753 166.41 305.223 165.283 304.094C165.001 303.814 164.694 303.562 164.366 303.341C164.035 303.127 163.687 302.941 163.325 302.785C162.962 302.63 162.583 302.514 162.196 302.439C161.37 302.271 160.518 302.278 159.695 302.461C158.872 302.643 158.096 302.996 157.418 303.496C156.741 303.997 156.175 304.635 155.759 305.368C155.343 306.1 155.085 306.913 155.003 307.751C154.92 308.59 155.014 309.437 155.28 310.237C155.545 311.037 155.975 311.772 156.542 312.396C157.109 313.019 157.801 313.516 158.573 313.856C159.344 314.195 160.178 314.368 161.021 314.365V314.367Z" fill="black"/>
									<path d="M351.464 308.349C351.464 306.752 350.829 305.22 349.7 304.09C348.57 302.96 347.038 302.326 345.44 302.326H279.181C277.584 302.326 276.052 302.96 274.922 304.09C273.793 305.22 273.158 306.752 273.158 308.349C273.158 309.947 273.793 311.479 274.922 312.608C276.052 313.738 277.584 314.373 279.181 314.373H345.44C347.038 314.373 348.57 313.738 349.7 312.608C350.829 311.479 351.464 309.947 351.464 308.349Z" fill="black"/>
									<path d="M260.488 334.336C260.488 335.934 261.122 337.466 262.252 338.596C263.381 339.725 264.914 340.36 266.511 340.36H321.175C322.772 340.36 324.304 339.725 325.434 338.596C326.564 337.466 327.198 335.934 327.198 334.336C327.198 332.739 326.564 331.207 325.434 330.077C324.304 328.947 322.772 328.313 321.175 328.313H266.511C264.914 328.313 263.381 328.947 262.252 330.077C261.122 331.207 260.488 332.739 260.488 334.336V334.336Z" fill="black"/>
									<path d="M256.438 361.06C256.438 362.657 257.073 364.189 258.202 365.319C259.332 366.449 260.864 367.083 262.462 367.083H293.841C295.439 367.083 296.971 366.449 298.101 365.319C299.23 364.189 299.865 362.657 299.865 361.06C299.865 359.462 299.23 357.93 298.101 356.8C296.971 355.671 295.439 355.036 293.841 355.036H262.462C260.864 355.036 259.332 355.671 258.202 356.8C257.073 357.93 256.438 359.462 256.438 361.06Z" fill="black"/>
									<path d="M330.031 361.06C330.031 359.462 329.396 357.93 328.266 356.8C327.137 355.671 325.605 355.036 324.007 355.036H321.173C319.575 355.036 318.043 355.671 316.914 356.8C315.784 357.93 315.149 359.462 315.149 361.06C315.149 362.657 315.784 364.189 316.914 365.319C318.043 366.449 319.575 367.083 321.173 367.083H324.007C325.605 367.083 327.137 366.449 328.266 365.319C329.396 364.189 330.031 362.657 330.031 361.06Z" fill="black"/>
									<path d="M315.918 406.46H293.841C292.244 406.46 290.712 407.095 289.582 408.225C288.452 409.354 287.818 410.886 287.818 412.484C287.818 414.082 288.452 415.614 289.582 416.743C290.712 417.873 292.244 418.508 293.841 418.508H315.918C317.515 418.508 319.047 417.873 320.177 416.743C321.307 415.614 321.941 414.082 321.941 412.484C321.941 410.886 321.307 409.354 320.177 408.225C319.047 407.095 317.515 406.46 315.918 406.46Z" fill="black"/>
								</svg>'
				]);
			break;							
			
			case 'order_count':
				array_push($res, [
					'title' => 'Cəmi satış (sayı)',
					'fields' => $type,
					'value' => decorate_num($order_count),
					'mark' 	=> [
						'mark_text' => 'ədəd',
						'mark_modify_class' => 'mark-right'
					],
					'icon' => '<svg width="448" height="448" viewBox="0 0 448 448" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M352 56V24C352 10.744 362.744 0 376 0H392C405.256 0 416 10.744 416 24V160.56C416 164.976 412.416 168.56 408 168.56C403.584 168.56 400 164.976 400 160.56V24C400 19.584 396.416 16 392 16H376C371.584 16 368 19.584 368 24V384H400V192.376C400 187.96 403.584 184.376 408 184.376C412.416 184.376 416 187.96 416 192.376V384H424C437.256 384 448 394.744 448 408V424C448 437.256 437.256 448 424 448H24C10.744 448 0 437.256 0 424V408C0 394.744 10.744 384 24 384H32V24C32 10.744 42.744 0 56 0H72C85.256 0 96 10.744 96 24V56H113.368C116.664 46.68 125.56 40 136 40C146.44 40 155.336 46.68 158.632 56H177.368C180.664 46.68 189.56 40 200 40C210.44 40 219.336 46.68 222.632 56H241.368C244.664 46.68 253.56 40 264 40C274.44 40 283.336 46.68 286.632 56H352ZM424 400H24C19.584 400 16 403.584 16 408V424C16 428.416 19.584 432 24 432H424C428.416 432 432 428.416 432 424V408C432 403.584 428.416 400 424 400ZM80 384V24C80 19.584 76.416 16 72 16H56C51.584 16 48 19.584 48 24V384H80ZM161.368 336H96V384H352V336H334.632C331.336 345.32 322.44 352 312 352C301.56 352 292.664 345.32 289.368 336H270.632C267.336 345.32 258.44 352 248 352C237.56 352 228.664 345.32 225.368 336H206.632C203.336 345.32 194.44 352 184 352C173.56 352 164.664 345.32 161.368 336ZM248 320C252.416 320 256 323.584 256 328C256 332.416 252.416 336 248 336C243.584 336 240 332.416 240 328C240 323.584 243.584 320 248 320ZM312 320C316.416 320 320 323.584 320 328C320 332.416 316.416 336 312 336C307.584 336 304 332.416 304 328C304 323.584 307.584 320 312 320ZM184 320C188.416 320 192 323.584 192 328C192 332.416 188.416 336 184 336C179.584 336 176 332.416 176 328C176 323.584 179.584 320 184 320ZM113.368 248H96V320H161.368C164.664 310.68 173.56 304 184 304C194.44 304 203.336 310.68 206.632 320H225.368C228.664 310.68 237.56 304 248 304C258.44 304 267.336 310.68 270.632 320H289.368C292.664 310.68 301.56 304 312 304C322.44 304 331.336 310.68 334.632 320H352V248H286.632C283.336 257.32 274.44 264 264 264C253.56 264 244.664 257.32 241.368 248H222.632C219.336 257.32 210.44 264 200 264C189.56 264 180.664 257.32 177.368 248H158.632C155.336 257.32 146.44 264 136 264C125.56 264 116.664 257.32 113.368 248ZM200 232C204.416 232 208 235.584 208 240C208 244.416 204.416 248 200 248C195.584 248 192 244.416 192 240C192 235.584 195.584 232 200 232ZM264 232C268.416 232 272 235.584 272 240C272 244.416 268.416 248 264 248C259.584 248 256 244.416 256 240C256 235.584 259.584 232 264 232ZM136 232C140.416 232 144 235.584 144 240C144 244.416 140.416 248 136 248C131.584 248 128 244.416 128 240C128 235.584 131.584 232 136 232ZM286.632 232H352V161.632H333.984C330.272 170.088 321.824 176 312 176C302.176 176 293.728 170.088 290.016 161.632H269.984C266.272 170.088 257.824 176 248 176C238.176 176 229.728 170.088 226.016 161.632H205.984C202.272 170.088 193.824 176 184 176C174.176 176 165.728 170.088 162.016 161.632H96V232H113.368C116.664 222.68 125.56 216 136 216C146.44 216 155.336 222.68 158.632 232H177.368C180.664 222.68 189.56 216 200 216C210.44 216 219.336 222.68 222.632 232H241.368C244.664 222.68 253.56 216 264 216C274.44 216 283.336 222.68 286.632 232ZM248 144C252.416 144 256 147.584 256 152C256 156.416 252.416 160 248 160C243.584 160 240 156.416 240 152C240 147.584 243.584 144 248 144ZM184 144C188.416 144 192 147.584 192 152C192 156.416 188.416 160 184 160C179.584 160 176 156.416 176 152C176 147.584 179.584 144 184 144ZM312 144C316.416 144 320 147.584 320 152C320 156.416 316.416 160 312 160C307.584 160 304 156.416 304 152C304 147.584 307.584 144 312 144ZM113.368 72H96V145.632H160.856C163.648 135.472 172.96 128 184 128C195.04 128 204.352 135.472 207.144 145.632H224.856C227.648 135.472 236.96 128 248 128C259.04 128 268.352 135.472 271.144 145.632H288.856C291.648 135.472 300.96 128 312 128C323.04 128 332.352 135.472 335.144 145.632H352V72H286.632C283.336 81.32 274.44 88 264 88C253.56 88 244.664 81.32 241.368 72H222.632C219.336 81.32 210.44 88 200 88C189.56 88 180.664 81.32 177.368 72H158.632C155.336 81.32 146.44 88 136 88C125.56 88 116.664 81.32 113.368 72ZM200 56C204.416 56 208 59.584 208 64C208 68.416 204.416 72 200 72C195.584 72 192 68.416 192 64C192 59.584 195.584 56 200 56ZM264 56C268.416 56 272 59.584 272 64C272 68.416 268.416 72 264 72C259.584 72 256 68.416 256 64C256 59.584 259.584 56 264 56ZM136 56C140.416 56 144 59.584 144 64C144 68.416 140.416 72 136 72C131.584 72 128 68.416 128 64C128 59.584 131.584 56 136 56Z" fill="black"/>
							   </svg>'
				]);
			break;							
		}
	}

	return $res;
}