const selectAll = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));

const heroSlider = () => {
	const slider = document.querySelector('[data-slider]');

	if (!slider) {
		return;
	}

	const slides = selectAll('.hero-slide', slider);
	const dots = selectAll('.hero-dots .dot', slider);
	const prevBtn = slider.querySelector('.hero-prev');
	const nextBtn = slider.querySelector('.hero-next');
	let current = 0;
	let intervalId;

	if (slides.length <= 1) {
		return;
	}

	const showSlide = (index) => {
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
		clearInterval(intervalId);
		intervalId = window.setInterval(next, 5000);
	};

	const stopAuto = () => {
		clearInterval(intervalId);
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

const showNotification = (message, type = 'info') => {
	const icons = {
		success: 'fa-check-circle',
		error: 'fa-times-circle',
		info: 'fa-info-circle',
	};

	const notification = document.createElement('div');
	notification.className = `notification ${type}`;
	notification.innerHTML = `
		<i class="fas ${icons[type] || icons.info}"></i>
		<span>${message}</span>
	`;

	document.body.appendChild(notification);

	window.setTimeout(() => {
		notification.classList.add('fade-out');
		notification.addEventListener('animationend', () => notification.remove(), { once: true });
	}, 3000);
};

const callApi = async (endpoint, method = 'POST', data) => {
	if (!endpoint) {
		throw new Error('Endpoint no disponible');
	}

	const options = {
		method,
		headers: {
			Accept: 'application/json',
			'X-Requested-With': 'XMLHttpRequest',
		},
		credentials: 'include',
	};

	const csrfToken = window.App?.csrfToken;
	if (csrfToken) {
		options.headers['X-CSRF-TOKEN'] = csrfToken;
	}

	if (method !== 'GET' && data) {
		options.headers['Content-Type'] = 'application/json';
		options.body = JSON.stringify(data);
	}

	const response = await fetch(endpoint, options);
	const raw = await response.text();
	let payload = raw;

	try {
		payload = raw ? JSON.parse(raw) : {};
	} catch (error) {
		payload = { success: false, message: raw };
	}

	if (!response.ok || payload?.success === false) {
		const message = payload?.message || payload?.error || `Error del servidor (${response.status})`;
		throw new Error(message);
	}

	return payload;
};

const toggleWishlistButton = (button, active) => {
	button.classList.toggle('active', active);
	const icon = button.querySelector('i');
	if (!icon) {
		return;
	}
	icon.classList.toggle('far', !active);
	icon.classList.toggle('fas', active);
};

const hydrateWishlistButtons = (items = []) => {
	items.forEach((item) => {
		const productId = typeof item === 'object' ? (item.product_id ?? item.productId ?? item.id) : item;
		if (!productId) {
			return;
		}
		const button = document.querySelector(`.wishlist-btn[data-product-id="${productId}"]`);
		if (button) {
			toggleWishlistButton(button, true);
		}
	});
};

const initWishlist = () => {
	const config = window.AngelowCatalog || {};
	const wishlistConfig = config.wishlist || {};
	const buttons = selectAll('.wishlist-btn');

	if (!buttons.length) {
		return;
	}

	const loadWishlist = async () => {
		if (!config.isAuthenticated || !wishlistConfig.list) {
			return;
		}

		try {
			const response = await callApi(wishlistConfig.list, 'GET');
			const items = response?.items || response?.data || response;
			hydrateWishlistButtons(Array.isArray(items) ? items : []);
		} catch (error) {
			console.error(error);
		}
	};

	buttons.forEach((button) => {
		button.addEventListener('click', async () => {
			if (!config.isAuthenticated) {
				showNotification('Debes iniciar sesión para usar la lista de deseos', 'error');
				return;
			}

			const productId = button.dataset.productId;
			const isActive = button.classList.contains('active');
			const endpoint = isActive ? wishlistConfig.remove : wishlistConfig.add;

			if (!endpoint) {
				showNotification('La lista de deseos estará disponible próximamente', 'info');
				return;
			}

			button.disabled = true;

			try {
				await callApi(endpoint, 'POST', { product_id: productId });
				toggleWishlistButton(button, !isActive);
				showNotification(
					isActive ? 'Producto eliminado de tu lista de deseos' : 'Producto añadido a tu lista de deseos',
					isActive ? 'info' : 'success',
				);
			} catch (error) {
				console.error(error);
				showNotification(error.message || 'No pudimos actualizar tu lista de deseos', 'error');
			} finally {
				button.disabled = false;
			}
		});
	});

	loadWishlist();
};

const initProductImages = () => {
	const fallback = window.AngelowCatalog?.fallbackImage;
	selectAll('.product-image').forEach((container) => {
		const img = container.querySelector('img');
		if (!img) {
			return;
		}

		container.classList.add('loading');

		const markLoaded = () => {
			img.classList.add('loaded');
			container.classList.remove('loading');
		};

		const handleError = () => {
			if (fallback) {
				img.src = fallback;
			}
			markLoaded();
		};

		if (img.complete) {
			if (img.naturalWidth) {
				markLoaded();
			} else {
				handleError();
			}
		} else {
			img.addEventListener('load', markLoaded, { once: true });
			img.addEventListener('error', handleError, { once: true });
		}
	});
};

document.addEventListener('DOMContentLoaded', () => {
	heroSlider();
	initWishlist();
	initProductImages();
});
