class CoverSlider {
	constructor(sliderBlock) {
		this.settings = JSON.parse(sliderBlock.dataset.settings)
		this.track = sliderBlock.querySelector('.slider-track')
		this.slides = [...this.track.children]

		console.log(this.settings)
	}
}

document
	.querySelectorAll('.wp-block-best4u-blocks-cover-slider')
	.forEach((sliderBlock) => {
		const settings = JSON.parse(sliderBlock.dataset.settings)
		const track = sliderBlock.querySelector('.slider-track')
		const slides = [...track.children]
		console.log({ settings })

		console.log({ slides })

		const getSlideWidth = () => {
			return slides[0].offsetWidth
		}

		const scrollToSlide = (index) => {
			const slide = slides[index]

			track.scrollTo({
				top: 0,
				left: index * getSlideWidth(),
				behavior: 'smooth',
			})
		}

		const slideBy = (slideCount) => {
			track.scrollBy({
				top: 0,
				left: slideCount * getSlideWidth(),
				behavior: 'smooth',
			})
		}

		if (settings.useDots) {
			const dotsContainer = sliderBlock.querySelector('.slider-dots')

			console.log({ dotsContainer })

			const createDot = (index) => {
				const dot = document.createElement('button')
				dot.classList.add('slider-dot')

				dot.addEventListener('click', () => {
					scrollToSlide(index)
				})

				return dot
			}

			slides.forEach((slide, index) => {
				dotsContainer.append(createDot(index))
			})
		}

		if (settings.useArrows) {
			const prevArrow = sliderBlock.querySelector('.slider-arrow-prev')
			const nextArrow = sliderBlock.querySelector('.slider-arrow-next')

			prevArrow.addEventListener('click', () => {
				slideBy(-1)
			})

			nextArrow.addEventListener('click', () => {
				slideBy(1)
			})
		}
	})
