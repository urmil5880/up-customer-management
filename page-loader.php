<div class="page-loader">
	<span></span>
	<span></span>
</div>

<style>

/* Page-Loader */
.page-loader {
  background: #fff;
  position: fixed;
  top: 0;
  bottom: 0;
  right: 0;
  left: 0;
  z-index: 9998;
}
.page-loader span {
    position: absolute;
    display: inline-block;
    background: #4282f4;
    height: 100px;
    width: 100px;
    left: 48%;
    top: 48%;
    margin: -20px 0 0 -20px;
    text-indent: -9999em;
    -webkit-border-radius: 100%;
    -moz-border-radius: 100%;
    border-radius: 100%;
    -webkit-animation:page-loader 1.2s linear infinite;
    animation:page-loader 1.2s linear infinite;
}
.page-loader span:last-child {
   animation-delay:-0.6s;
   -webkit-animation-delay:-0.6s;
}
@media(max-width:991px){
    .page-loader span {
        left: 46%;
        top: 46%;
    }
}
@media(max-width:768px){
    .page-loader span {
        left: 44%;
        top: 44%;
    }
}
@media(max-width:575px){
    .page-loader span {
        left: 42%;
        top: 42%;
    }
}
@keyframes page-loader {
   0% {transform: scale(0, 0);opacity:0.8;}
   100% {transform: scale(1, 1);opacity:0;}
}
@-webkit-keyframes page-loader {
   0% {-webkit-transform: scale(0, 0);opacity:0.8;}
   100% {-webkit-transform: scale(1, 1);opacity:0;}
}
</style>
<scrpit>
 /* Preloader */
	$(window).load(function() {
		$('.span').fadeOut();
		$('.page-loader').delay(350).fadeOut('slow');
	});

</script>
