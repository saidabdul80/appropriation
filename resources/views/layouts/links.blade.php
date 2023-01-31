<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/images/e_portal.png">
	<link rel="stylesheet" href="/lib/bootstrap-icons/font/bootstrap-icons.css">
	<!-- <link rel="stylesheet" href="/lib/css/bootstrap.min.css"> -->
	<!-- 
		-->
		@vite(['resources/sass/app.scss', 'resources/js/app.js'])
		<link rel="stylesheet" href="/lib/css/select2.css">
		<link rel="stylesheet" href="/lib/css/swift-menu.css">
		<link rel="stylesheet" href="/lib/js/datatable.css">
		<link rel="stylesheet" href="/lib/css/font-awesome-4.7.0/css/font-awesome.min.css">
	<!-- <link href="/lib/js/listjs/PagingStyle.css" rel="stylesheet" /> -->
		<!-- <link rel="stylesheet" href="/lib/css/vue-tagin.css"> -->
	<script src="/vendor/jquery/jquery-3.2.1.min.js"></script>
	
	<script src="/lib/js/swift-menu.js"></script>
	<script src="/lib/js/jspdf.js"></script>

	<script src="/lib/css/sweetalert.css"></script>
	<!-- <script src="/lib/js/listjs/paging.js"></script> -->
	<script src="/lib/js/datatable/datatables.min.js" defer></script>
	<script type="text/javascript" src="/lib/js/datatable/pdfmake.min.js"></script>
	<script type="text/javascript" src="/lib/js/datatable/vfs_fonts.js"></script>	
	<script type="text/javascript" src="/lib/js/select2.js"></script>
	<script src="/lib/js/jqueryvalidate.js"></script>
	<!-- <script src="/lib/js/propper.js"></script> -->
	<script src="/lib/js/sweetalert.js"></script>
	<link rel="stylesheet" href="/lib/css/boostrap4.css">
	<script src="/lib/js/bootstrap4.js"></script>
	<script src="/lib/js/vue.js"></script>
	<script src="/lib/js/vue-tagins.js" ></script>

	<script src="/lib/js/axios.js"></script>
	<!-- <script src="/lib/js/datatable.css"></script> -->
	<link rel="stylesheet" href="/lib/css/styles.css">
	<script src="/lib/js/fullscreen.js"></script>
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Smartresult') }}</title>

	<!-- Styles -->
	<style>

	</style>
	<script>
		$(document).ready(function() {
			var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
			var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {				
				return new bootstrap.Dropdown(dropdownToggleEl)
			})

			/* var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
			var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			}) */

			setTimeout(function() {
				switchPage()
			}, 600)
		})

		function showLoader() {
			$("#loaderHtml").show()
		}

		function hideLoader() {
			$("#loaderHtml").hide()
		}

		function switchPage(type = 1) {
			if (type == 1) {
				$("#appIn").css('visibility', 'visible');
				$("#loaderHtml").hide()
			} else {
				$("#loaderHtml").show()
				$("#appIn").css('visibility', 'hidden');
			}
		}

		function showAlert(msg, icon = 'success') {
			const Toast = Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
				didOpen: (toast) => {
					toast.addEventListener('mouseenter', Swal.stopTimer)
					toast.addEventListener('mouseleave', Swal.resumeTimer)

					//toast.addexpenseListener('mouseenter', Swal.stopTimer)
					//toast.addexpenseListener('mouseleave', Swal.resumeTimer)
				}
			})

			Toast.fire({
				icon: icon,
				title: msg
			})
		}
		var loaderHtml = ''

		async function postData(route, data, type = false) {
			$("#loaderHtml").show()
			return await axios.post(route, data).then(function(response) {
				//switchPage(1)
				$("#loaderHtml").hide()
				if (!type) { //if false
					showAlert(response.data);
				} else {
					return response;
				}
			}).catch(function(error) {
				//switchPage(1)                   
				$("#loaderHtml").hide()
				Swal.fire({
					text: error.response.data,
					icon: 'error',
				})
			})
		}

		function formatToCurrency(amount) {
			return (amount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
		}

		function PrintElem(id, title) {

			var mywindow = window.open('', 'PRINT','left=0,top=0,toolbar=0,scrollbars=0,status=0');

			mywindow.document.write('<html><head><title>' + title + '</title>');
			mywindow.document.write(`<link rel="stylesheet" href="{{asset('/lib/css/styles.css')}}"  >`);
			mywindow.document.write(`<link rel="stylesheet" href="{{asset('/lib/css/boostrap4.css')}} "  >`);
			mywindow.document.write(`</head><body>`);
			mywindow.document.write('<h1 class="text-center">' + title + '</h1>');
			let html = $('#' + id).html();
			html = html.replace('height', '');
			html = html.replace('overflow', '');
			mywindow.document.write(html);
			mywindow.document.write('</body></html>');
			mywindow.document.close(); // necessary for IE >= 10
			mywindow.focus(); // necessary for IE >= 10*/

			setTimeout(() => {
				mywindow.print();
				mywindow.close();
			}, 2000);
			return true;
		}
		window.jsPDF = window.jspdf.jsPDF;

		function Convert_HTML_To_PDF(id) {
			var doc = new jsPDF({
				orientation: 'l',
				unit: 'mm',
				format: 'a4',
				putOnlyUsedFonts: true
			});

			// Source HTMLElement or a string containing HTML.
			var elementHTML = document.querySelector("#" + id);

			doc.html(elementHTML, {

				callback: function(doc) {
					// Save the PDF
					window.open(doc.output('bloburl'));
				},
				margin: [10, 10, 10, 10],
				autoPaging: 'text',
				/* width: 190, //target width in the PDF document
        windowWidth: 675, //window width in CSS pixels */
				html2canvas: {
					scale: 0.22
				},
				dpi: 300,
			});

			
		}
		async function postDataWithAlert(url, data,elt ,wmsg='Are you sure you want to continue', smsg='success') {
				Swal.fire({
					text: wmsg,
					showCancelButton: true,
					showLoaderOnConfirm: true,
					preConfirm: async (value) => {
						if (value) {
							let res = await postData(url, data, true)
							return true;
						}
					},
					allowOutsideClick: () => !Swal.isLoading()
				}).then((result) => {
					if (result.isConfirmed) {
						showAlert(smsg);
						return true;
					}else{
						elt.checked = false;
					}

				})
			}
	</script>
</head>

<body>
	<div id="">
		@yield('body')
	</div>
</body>

</html>