$(document).ready(function(){
    var slides = [{src: 'images/iconfont/tuceng01.png'}, {src: 'images/iconfont/tuceng02.png'}, {src: 'images/iconfont/tuceng03.png'},{src: 'images/iconfont/tuceng04.png'}]
    var jR3DCarousel;
    var carouselProps =  {
                      width: 700,               /* largest allowed width */
                      height: 300,              /* largest allowed height */
                      slideLayout : 'fill',     /* "contain" (fit according to aspect ratio), "fill" (stretches object to fill) and "cover" (overflows box but maintains ratio) */
                      animation: 'slide3D',         /* slide | scroll | fade | zoomInSlide | zoomInScroll */
                      animationCurve: 'ease',
                      animationDuration: 700,
                      animationInterval: 1000,
                      //slideClass: 'jR3DCarouselCustomSlide',
                      autoplay: true,
                      onSlideShow: show,        /* callback when Slide show event occurs */
                      navigation: 'circles',    /* circles | squares */
                      slides: slides            /* array of images source or gets slides by 'slide' class */
                          
                }
    function setUp(){
        jR3DCarousel = $('.jR3DCarouselGallery').jR3DCarousel(carouselProps);

        $('.settings').html('<pre>$(".jR3DCarouselGallery").jR3DCarousel('+JSON.stringify(carouselProps, null, 4)+')</pre>');       
        
    }
    function show(slide){
        // console.log("Slide shown: ", slide.find('img').parent().attr('data-index'))
        // alert($(".jR3DCarouselSlide").attr('data-index'));
    }
    $('.carousel-props input').change(function(){
        if(isNaN(this.value))
            carouselProps[this.name] = this.value || null; 
        else
            carouselProps[this.name] = Number(this.value) || null; 
        
        for(var i = 0; i < 999; i++)
         clearInterval(i);
        $('.jR3DCarouselGallery').empty();
        setUp();
        jR3DCarousel.showNextSlide();
    })
    
    $('[name=slides]').change(function(){
        carouselProps[this.name] = getSlides(this.value); 
        for (var i = 0; i < 999; i++)
         clearInterval(i);
        $('.jR3DCarouselGallery').empty();
        setUp();
        jR3DCarousel.showNextSlide();       
    });
    
    setUp()

  })