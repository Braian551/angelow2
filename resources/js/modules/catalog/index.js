const heroSlider = () => {
	const slider = document.querySelector('[data-slider]');

	if (!slider) {
		return;
	}

	const slides = Array.from(slider.querySelectorAll('.hero-slide'));
	const dots = Array.from(slider.querySelectorAll('.hero-dots .dot'));
	const prevBtn = slider.querySelector('.hero-prev');
	const nextBtn = slider.querySelector('.hero-next');
	let current = 0;
	let intervalId;

	const showSlide = (index) => {
		if (!slides.length) {
			return;
		}

		current = (index + slides.length) % slides.length;

		slides.forEach((slide, position) => {
			slide.classList.toggle('active', position === current);
		});

		dots.forEach((dot, position) => {
			dot.classList.toggle('active', position === current);
		});
	};

	const next = () => showSlide(current + 1);
	const prev = () => showSlide(current - 1);

	const startAuto = () => {
		if (intervalId) {
			clearInterval(intervalId);
		}
		intervalId = setInterval(next, 5000);
	};

	const stopAuto = () => {
		if (intervalId) {
			clearInterval(intervalId);
		}
	};

	nextBtn?.addEventListener('click', () => {
		next();
		startAuto();
	});

	prevBtn?.addEventListener('click', () => {
		prev();
		startAuto();
	});

	dots.forEach((dot, index) => {
		dot.addEventListener('click', () => {
			showSlide(index);
			startAuto();
		});
	});

	slider.addEventListener('mouseenter', stopAuto);
	slider.addEventListener('mouseleave', startAuto);

	startAuto();
};

document.addEventListener('DOMContentLoaded', () => {
	heroSlider();
});
