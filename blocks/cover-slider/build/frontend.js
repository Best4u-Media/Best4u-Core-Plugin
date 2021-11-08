!function(){class t{constructor(t){this.sliderBlock=t,this.settings={autoplaySpeed:5e3,...JSON.parse(t.dataset.settings)},this.track=t.querySelector(".slider-track"),this.slides=[...this.track.children],this.autoplay=null,this.settings.useArrows&&this.initArrows(),this.settings.useDots&&this.initDots(),this.settings.enableAutoplay&&this.initAutoplay()}getSlideWidth(){return this.slides[0].offsetWidth}scrollToSlide(t){const s=this.getSlideWidth()*t;this.track.scrollTo({left:s,behavior:"smooth"})}getCurrentSlideIndex(){const t=this.getSlideWidth(),s=this.track.scrollLeft;return Math.round(s/t)}slideBy(t){let s=this.getCurrentSlideIndex()+t;s>=this.slides.length?s=0:s<0&&(s=this.slides.length-1),this.scrollToSlide(s)}initDots(){this.dotsContainer=this.sliderBlock.querySelector(".slider-dots");const t=this.slides.length;for(let s=0;s<t-1;s++){const t=this.createDot();this.dotsContainer.appendChild(t)}this.dots=[...this.dotsContainer.children],this.dots.forEach(((t,s)=>{t.addEventListener("click",(()=>{this.scrollToSlide(s)}))})),this.track.addEventListener("scroll",(()=>{this.setActiveDot()}),{passive:!0}),this.setActiveDot()}createDot(){const t=document.createElement("button");return t.classList.add("slider-dot"),t}initArrows(){const t=this.sliderBlock.querySelector(".slider-arrow-prev"),s=this.sliderBlock.querySelector(".slider-arrow-next");t.addEventListener("click",(()=>{this.slideBy(-1),clearInterval(this.autoplay)})),s.addEventListener("click",(()=>{this.slideBy(1),clearInterval(this.autoplay)}))}initAutoplay(){this.startAutoplay(),this.track.addEventListener("touchstart",(()=>{this.stopAutoplay()})),this.track.addEventListener("touchend",(()=>{this.startAutoplay()})),this.settings.autoplayPauseOnHover&&(this.track.addEventListener("mouseenter",(()=>{this.stopAutoplay()})),this.track.addEventListener("mouseleave",(()=>{this.startAutoplay()})))}startAutoplay(){this.autoplay=setInterval((()=>{this.slideBy(1)}),this.settings.autoplaySpeed)}stopAutoplay(){clearInterval(this.autoplay)}setActiveDot(){const t=this.getCurrentSlideIndex();this.dots.forEach(((s,e)=>{e===t?s.classList.add("active"):s.classList.remove("active")}))}}document.querySelectorAll(".wp-block-best4u-blocks-cover-slider").forEach((s=>new t(s)))}();