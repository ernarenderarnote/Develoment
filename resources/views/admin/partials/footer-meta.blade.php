@include('js-localization::head')
@yield('js-localization.head')

<script src="/js/admin.js"></script>
<script src="/js/admin-spark.js"></script>
{!! Rapyd::scripts() !!}

<script>
	$(document).ready(function(e){ 
		$(".size-check").click(function(){
			$(this).closest('label').toggleClass("check");
		});
		$('.color-check').on('click',function(){
			$(this).closest('label').toggleClass('active');
		});
		$('body').on('click',function(){
			//$('#modal-notifications').hide();
		});
		
	});

</script>    
