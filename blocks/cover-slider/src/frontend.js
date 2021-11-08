class CoverSlider {
	constructor(sliderBlock) {
		this.sliderBlock = sliderBlock
		this.settings = {
			autoplaySpeed: 5000,
			...JSON.parse(sliderBlock.dataset.settings),
		}
		this.track = sliderBlock.querySelector('.slider-track')
		this.slides = [...this.track.children]
		this.autoplay = null

		if (this.settings.useArrows) {
			this.initArrows()
		}
		if (this.settings.useDots) {
			this.initDots()
		}
		if (this.settings.enableAutoplay) {
			this.initAutoplay()
		}
	}

	getSlideWidth() {
		return this.slides[0].offsetWidth
	}

	scrollToSlide(index) {
		const slideWidth = this.getSlideWidth()
		const offset = slideWidth * index
		this.track.scrollTo({
			left: offset,
			behavior: 'smooth',
		})
	}

	getCurrentSlideIndex() {
		const slideWidth = this.getSlideWidth()
		const currentOffset = this.track.scrollLeft
		const index = Math.round(currentOffset / slideWidth)

		return index
	}

	slideBy(slideCount) {
		const index = this.getCurrentSlideIndex()
		let newIndex = index + slideCount
		if (newIndex >= this.slides.length) {
			newIndex = 0
		} else if (newIndex < 0) {
			newIndex = this.slides.length - 1
		}

		this.scrollToSlide(newIndex)
	}

	initDots() {
		this.dotsContainer = this.sliderBlock.querySelector('.slider-dots')
		const slideCount = this.slides.length
		for (let i = 0; i < slideCount - 1; i++) {
			const dot = this.createDot()
			this.dotsContainer.appendChild(dot)
		}

		this.dots = [...this.dotsContainer.children]

		this.dots.forEach((dot, index) => {
			dot.addEventListener('click', () => {
				this.scrollToSlide(index)
			})
		})

		this.track.addEventListener(
			'scroll',
			() => {
				this.setActiveDot()
			},
			{
				passive: true,
			}
		)

		this.setActiveDot()
	}

	createDot() {
		const dot = document.createElement('button')
		dot.classList.add('slider-dot')

		return dot
	}

	initArrows() {
		const prevArrow = this.sliderBlock.querySelector('.slider-arrow-prev')
		const nextArrow = this.sliderBlock.querySelector('.slider-arrow-next')

		prevArrow.addEventListener('click', () => {
			this.slideBy(-1)
			clearInterval(this.autoplay)
		})

		nextArrow.addEventListener('click', () => {
			this.slideBy(1)
			clearInterval(this.autoplay)
		})
	}

	initAutoplay() {
		this.startAutoplay()

		this.track.addEventListener('touchstart', () => {
			this.stopAutoplay()
		})

		this.track.addEventListener('touchend', () => {
			this.startAutoplay()
		})

		if (!this.settings.autoplayPauseOnHover) {
			return
		}

		this.track.addEventListener('mouseenter', () => {
			this.stopAutoplay()
		})

		this.track.addEventListener('mouseleave', () => {
			this.startAutoplay()
		})
	}

	startAutoplay() {
		this.autoplay = setInterval(() => {
			this.slideBy(1)
		}, this.settings.autoplaySpeed)
	}

	stopAutoplay() {
		clearInterval(this.autoplay)
	}

	setActiveDot() {
		const index = this.getCurrentSlideIndex()
		this.dots.forEach((dot, i) => {
			if (i === index) {
				dot.classList.add('active')
			} else {
				dot.classList.remove('active')
			}
		})
	}
}

document
	.querySelectorAll('.wp-block-best4u-blocks-cover-slider')
	.forEach((sliderBlock) => new CoverSlider(sliderBlock))
