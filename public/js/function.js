(function ($) {
    "use strict";
	
	var $window = $(window); 
	var $body = $('body'); 

	/* Preloader Effect JS */
	$(window).on("load", function () {
		setTimeout(function () {
			$(".preloader").fadeOut(1000); 
		}, 700); 
		sisfRippleHover.init();
	});
	
	/* Sticky Header JS */	
	if($('.active-sticky-header').length) {
		
		$window.on('resize', function(){
			setHeaderHeight();
		});

		function setHeaderHeight(){
	 		$("header.main-header").css("height", $('header .header-sticky').outerHeight());
		}	
	
		$(window).on("scroll", function() {
			var fromTop = $(window).scrollTop();
			setHeaderHeight();
			var headerHeight = $('header .header-sticky').outerHeight()
			$("header .header-sticky").toggleClass("hide", (fromTop > headerHeight + 100));
			$("header .header-sticky").toggleClass("active", (fromTop > 600));
		});
	}

	/* Mobile Menu Handling */
	const initialMenuItems = $('#menu > li').toArray();
	const initialMenu2Items = $('#menu2 > li').toArray();

	const handleMobileMenus = () => {
        const isMobile = $window.width() <= 768;
        const hasSlickNav = $(".slicknav_nav").length > 0;

        if (isMobile && !hasSlickNav) {
            $("#menu2").children().appendTo("#menu");
            $("#menu").slicknav({ label: "", prependTo: ".responsive-menu" });
        } else if (!isMobile && hasSlickNav) {
            $("#menu").slicknav("destroy");

            $("#menu > li").not(initialMenuItems).appendTo("#menu2");
            initialMenu2Items.forEach((item) => $(item).appendTo("#menu2"));
            initialMenuItems.forEach((item) => $(item).appendTo("#menu"));
        }
    };

	/* Run the function on page load */
    handleMobileMenus();
	
	if($(".orderby").length > 0 ) {
		$(".orderby").select2({ minimumResultsForSearch: Infinity });
	}
	let resizeTimeout;

	/* Re-run the function on window resize */
	$window.on("resize", function () {
		clearTimeout(resizeTimeout);
		resizeTimeout = setTimeout(handleMobileMenus, 200); // Delay execution
	});
	
	/* Scroll to Top */
    $(document).on("click", "a[href='#top']", function (e) {
        e.preventDefault();
        $("html, body").animate({ scrollTop: 0 }, "slow");
    });

	/* Initialize Swiper Sliders */
    const initSwiper = (selector, options) => {
        if ($(selector).length) {
            return new Swiper(selector, options);
        }
        return null;
    };

	const swiperOptions = {
        slidesPerView: 1,
        speed: 1000,
        spaceBetween: 10,
        loop: true,
        autoplay: { delay: 5000 },
    };


    /* Hero Slider Start */
	initSwiper(".hero-slider-layout .swiper", {
        ...swiperOptions,
        autoplay: { delay: 5000 },
        pagination: { el: ".hero-pagination", clickable: true },
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev"
        },
        on: {
            init: function () {
                animateActiveSlideText(); 
            },
            slideChangeTransitionStart: function () {
                animateActiveSlideText(); 
            }
        }
    });

    function animateActiveSlideText() {
        gsap.set(".text-anime-style-2", { clearProps: "all" });

        const activeSlide = document.querySelector(".swiper-slide-active");
        const animatedTextElements = activeSlide.querySelectorAll(".text-anime-style-2");

        animatedTextElements.forEach((element) => {
            const animationSplitText = new SplitText(element, { type: "chars, words" });

            gsap.from(animationSplitText.chars, {
				opacity: 0,
                duration: 0.16,         
				delay: 0.2,
				x: 250,                 
				autoAlpha: 0,
				stagger: 0.09,         
				ease: "power5.out",
            });
        });
    }
    /* Hero Slider End */

	/* Back To Top Button */
    const backToTop = document.getElementById('backToTop');
	window.addEventListener('scroll', () => {
		if (window.scrollY > 300) {
			backToTop.classList.add('show');
		} else {
			backToTop.classList.remove('show');
		}
	});

	backToTop.addEventListener('click', function (e) {
		e.preventDefault();
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	});

	/* Skill Bar */
	if ($('.skills-progress-bar').length) {
		let animated = false;

		$('.skills-progress-bar').waypoint(function () {
				if (!animated) {
					animated = true;

					$('.skillbar').each(function () {
						const $this = $(this);
						const percent = parseInt($this.attr('data-percent'));

						const $countBar = $this.find('.count-bar');
						const $countText = $this.find('.skill-no');

						// Set bar to 0% width initially
						$countBar.css('width', '0');

						// Animate bar width
						$countBar.animate({
							width: percent + '%'
						}, {
							duration: 2000,
							easing: 'swing'
						});

						// Animate number from 0 to percent
						$({ Counter: 0 }).animate({ Counter: percent }, {
							duration: 2000,
							easing: 'swing',
							step: function (now) {
								$countText.text(Math.ceil(now) + '%');
							}
						});
					});
				}
			}, {
			offset: '50%'
		});
    }

	/* Youtube Background Video JS */
	if ($('#herovideo').length) {
		var myPlayer = $("#herovideo").YTPlayer();
	}

	/* Audio JS */
	const player = new Plyr('#player');

	/* Init Counter */
	if ($('.counter').length) {
		$('.counter').counterUp({ delay: 6, time: 3000 });
	}

	/* Image Reveal Animation */
	if ($('.reveal').length) {
        gsap.registerPlugin(ScrollTrigger);
        let revealContainers = document.querySelectorAll(".reveal");
        revealContainers.forEach((container) => {
            let image = container.querySelector("img");
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: container,
                    toggleActions: "play none none none"
                }
            });
            tl.set(container, {
                autoAlpha: 1
            });
            tl.from(container, 1, {
                xPercent: -100,
                ease: Power2.out
            });
            tl.from(image, 1, {
                xPercent: 100,
                scale: 1,
                delay: -1,
                ease: Power2.out
            });
        });
    }

	/* Text Effect Animation */
	if ($('.text-anime-style-1').length) {
		let staggerAmount 	= 0.05,
		translateXValue = 0,
		delayValue 		= 0.5,
		animatedTextElements = document.querySelectorAll('.text-anime-style-1');
	
		animatedTextElements.forEach((element) => {
			let animationSplitText = new SplitText(element, { type: "chars, words" });
			gsap.from(animationSplitText.words, {
				duration: 1,
				delay: delayValue,
				x: 20,
				autoAlpha: 0,
				stagger: staggerAmount,
				scrollTrigger: { trigger: element, start: "top 85%" },
			});
		});
	}
	
	if ($('.text-anime-style-3').length) {		
		let	animatedTextElements = document.querySelectorAll('.text-anime-style-3');
		
		 animatedTextElements.forEach((element) => {
			//Reset if needed
			if (element.animation) {
				element.animation.progress(1).kill();
				element.split.revert();
			}

			element.split = new SplitText(element, {
				type: "lines,words,chars",
				linesClass: "split-line",
			});
			gsap.set(element, { perspective: 400 });

			gsap.set(element.split.chars, {
				opacity: 0,
				x: "50",
			});

			element.animation = gsap.to(element.split.chars, {
				scrollTrigger: { trigger: element,	start: "top 90%" },
				x: "0",
				y: "0",
				rotateX: "0",
				opacity: 1,
				duration: 1,
				ease: Back.easeOut,
				stagger: 0.02,
			});
		});
	}

	/* Parallaxie JS */
	var $parallaxie = $('.parallaxie');
	if($parallaxie.length && ($window.width() > 991))
	{
		if ($window.width() > 768) {
			$parallaxie.parallaxie({
				speed: 0.55,
				offset: 0,
			});
		}
	}

	/* Zoom Gallery Screenshot JS */
	$('.gallery-items').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom',
		image: {
			verticalFit: true,
		},
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, 
			opener: function(element) {
			  return element.find('img');
			}
		}
	});

	/* Contact Form Validation JS */
	$("#contactForm").validator({ focus: false }).on("submit", function (event) {
        if (!event.isDefaultPrevented()) {
            event.preventDefault();
            submitForm("#contactForm", "../luxestay-html/form-process.php", contactFormSuccess);
        }
    });
	const submitForm = (formId, url, successCallback) => {
        const formData = $(formId).serialize();
        $.post(url, formData, (response) => {
			if (typeof response === "string" && response.trim() === "success") {
				successCallback();
			} else {
				showMsg(false, response);
			}
		});
    };

	const contactFormSuccess = () => {
        $("#contactForm")[0].reset();
        showMsg(true, "Message Sent Successfully!");
    };

    const showMsg = (valid, msg) => {
        $("#msgSubmit").removeClass().addClass(valid ? "text-success" : "text-danger").text(msg);
    };
	/* End - Contact Form Validation JS */

	/* Animated Wow Js */	
	new WOW().init();

	/* Popup Video JS */
	if ($('.popup-video').length) {
		$('.popup-video').magnificPopup({
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: true
		});
	}
	var sisfRippleEffect = {
		init: function () {
			var titleHolder = $(".sisf-page-title");
			if ( titleHolder.hasClass("sisf-title--ripple") ) {
				titleHolder.ripples({
					resolution: 512,
					dropRadius: 20,
					perturbance: 1.8,
				});
			}
		}
	}
	sisfRippleEffect.init();

	/* Five Swiper Slider JS */
	initSwiper(".comman--swiper-slider .swiper", {
		...swiperOptions,
		breakpoints: {
			0: {
				slidesPerView: 1,
				centeredSlides: false
			},
			768: {
				slidesPerView: 3,
				centeredSlides: true
			},
			1024: {
				slidesPerView: 5,
				centeredSlides: true
			}
		}
	});

	/* Four Swiper Slider JS */
	initSwiper(".comman-swiper-slider .swiper", {
		...swiperOptions,
		navigation: {
			nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
		},
		breakpoints: {
			0: {
				slidesPerView: 1,
			},
			768: {
				slidesPerView: 2,
			},
			1024: {
				slidesPerView: 4,
			}
		}
	});

	/* Three Slider JS */
	initSwiper(".sisf-sis-slider .swiper", {
		...swiperOptions,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev"
		},
		breakpoints: {
			0: {
				slidesPerView: 1,
				centeredSlides: false
			},
			768: {
				slidesPerView: 2,
				centeredSlides: false
			},
			1024: {
				slidesPerView: 3,
				centeredSlides: false
			}
		}
	});

	/* Two Slider JS */
	initSwiper(".sisf--sis-slider .swiper", {
		...swiperOptions,
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev"
		},
		breakpoints: {
			0: {
				slidesPerView: 1,
				centeredSlides: false
			},
			768: {
				slidesPerView: 2,
				centeredSlides: false
			},
			1024: {
				slidesPerView: 2,
				centeredSlides: false
			}
		}
	});

	/* single slider JS */
	document.querySelectorAll('.sisf-single-slider').forEach((sliderContainer, index) => {
		const sliderIndex = index + 1;

		const swiper = new Swiper(sliderContainer.querySelector('.swiper'), {
			...swiperOptions,
			navigation: {
				nextEl: sliderContainer.querySelector(`.custom-icon-right-${sliderIndex}`),
				prevEl: sliderContainer.querySelector(`.custom-icon-left-${sliderIndex}`)
			},
			pagination: {
				el: sliderContainer.querySelector('.swiper-pagination'),
				clickable: true,
			},
			breakpoints: {
				0: {
					slidesPerView: 1,
					centeredSlides: false
				},
				768: {
					slidesPerView: 1,
					centeredSlides: true
				},
				1024: {
					slidesPerView: 1,
					centeredSlides: true
				}
			}
		});
	});


	/* Section Title Scroll Animation JS */
	if ( $( '.sisf-m-title--scroll' ).length ) {
        gsap.registerPlugin(ScrollTrigger);
        let sisSectionTitles = document.querySelectorAll(".sisf-m-title--scroll");
        if (sisSectionTitles.length > 0) {
			sisSectionTitles.forEach((container) => {
				var text = new SplitText(container, { type: 'words, chars' });
				text.words.forEach((word) => {
					if (word.children.length > 0) {
						word.children[0].classList.add("first-char");
					}
				});
				gsap.fromTo(text.chars,
					{
						position: 'relative',
						display: 'inline-block',
						opacity: 0.2,
						x: -7,
					},
					{
						opacity: 1,
						x: 0,
						stagger: 0.1,
						scrollTrigger: {
							trigger: container,
							toggleActions: "play pause reverse pause",
							start: "top 90%",
							end: "top 40%",
							scrub: 0.7,
						}
					}
				);
			});
		}
    }

	// Initialize Flatpickr For Date Js
		flatpickr("#checkin", {
		defaultDate: "2025-05-14"
	});

	flatpickr("#checkout", {
		defaultDate: "2025-05-15"
	});

	// Ripple Water Effect Js
	var sisfRippleHover = {
		init: function () {
			const galleries = document.querySelectorAll('.sisf-sis-image-gallery-masonry.sisf-ripple-effect');

			galleries.forEach(gallery => {
				const items = gallery.querySelectorAll('.sisf-e-item:not(.sisf-has-ripple-effect)');

				items.forEach(item => {
					item.classList.add('sisf-has-ripple-effect');
					item.style.position = 'relative';

					const img = item.querySelector('img');
					if (!img) return;

					const width = img.clientWidth;
					const height = img.clientHeight;

					// PIXI Application
					const app = new PIXI.Application({
						width: width,
						height: height,
						transparent: true
					});

					// Style canvas
					const canvas = app.view;
					canvas.style.position = 'absolute';
					canvas.style.top = 0;
					canvas.style.left = 0;
					canvas.style.pointerEvents = 'none';
					canvas.style.opacity = 0;
					canvas.style.transition = 'opacity 0.3s ease';
					item.appendChild(canvas);

					// Add image
					const sprite = PIXI.Sprite.from(img.src);
					sprite.width = width;
					sprite.height = height;
					app.stage.addChild(sprite);

					// Displacement sprite
					const displacement = PIXI.Sprite.from('images/ripple-hover-pattern.jpg');
					displacement.texture.baseTexture.wrapMode = PIXI.WRAP_MODES.REPEAT;
					displacement.scale.set(2);
					app.stage.addChild(displacement);

					// Filter
					const filter = new PIXI.filters.DisplacementFilter(displacement);
					app.stage.filters = [filter];

					// Animate only when hovered
					let isHovering = false;

					app.ticker.add(() => {
						if (isHovering) {
							displacement.x += 1;
							displacement.y += 1;
						}
					});

					// Mouse events
					item.addEventListener('mouseenter', () => {
						isHovering = true;
						canvas.style.opacity = 1;
					});

					item.addEventListener('mouseleave', () => {
						isHovering = false;
						canvas.style.opacity = 0;
					});
				});
			});
		}
	};

	/* Full Width Calender JS */
	function initResponsiveCalendar() {
		const calendarEl = document.getElementById("static-calendar");

		// Only initialize if calendar element exists
		if (!calendarEl) return;

		// Destroy any existing flatpickr instance
		if (calendarEl._flatpickr) {
			calendarEl._flatpickr.destroy();
		}
		// Determine number of months based on screen width
		const isMobile = window.innerWidth < 768;
		flatpickr(calendarEl, {
			inline: true,
			mode: "range",
			showMonths: isMobile ? 1 : 2,
			minDate: "today",
			dateFormat: "d M Y",
			defaultDate: ["2025-07-24", "2025-08-02"]
		});
	}

	// Init on load
	document.addEventListener("DOMContentLoaded", () => {
		initResponsiveCalendar();

		// Re-init on resize
		window.addEventListener('resize', () => {
			clearTimeout(window._resizeTimer);
			window._resizeTimer = setTimeout(() => {
			initResponsiveCalendar();
			}, 200);
		});
	});

	// Room Single form section Services Part Js
    document.addEventListener("DOMContentLoaded", function () {
		const serviceCheckboxes = document.querySelectorAll(".service");
		const totalPriceEl = document.getElementById("total-price");
		// Guest count selectors
		const adultSelect = document.querySelector("#guests");
		const childrenSelect = document.querySelectorAll(".dropdown-menu select")[1]; // assuming it's 2nd select in dropdown
		// Only initialize if totalPriceEl element exists
		if (!totalPriceEl) return;

		function getGuestCount() {
			const adults = parseInt(adultSelect.value) || 0;
			const children = parseInt(childrenSelect.value) || 0;
			return adults + children;
		}

		function updateTotalPrice() {
			const basePrice = 399;
			let total = basePrice;
			const guestCount = getGuestCount();

			serviceCheckboxes.forEach((checkbox) => {
				if (checkbox.checked) {
				const price = parseFloat(checkbox.dataset.price) || 0;
				const label = checkbox.parentElement.textContent.trim();

				if (label.includes("Room Cleaning")) {
					total += price; // flat rate
				} else if (
					label.includes("Bike Rental") ||
					label.includes("Airport Transport") ||
					label.includes("Massage")
				) {
					total += price * guestCount; // per guest
				}
				// Parking is free — ignore
				}
			});

			totalPriceEl.textContent = total.toFixed(2);
		}

		// Event listeners
		serviceCheckboxes.forEach((checkbox) => {
			checkbox.addEventListener("change", updateTotalPrice);
		});

		adultSelect.addEventListener("change", updateTotalPrice);
		childrenSelect.addEventListener("change", updateTotalPrice);

		// Initial price calculation
		updateTotalPrice();
    });


	/* Product Quantity Plus Minus JS */
	$(document).on("click", ".sisf-quantity-minus, .sisf-quantity-plus", function (e) {
        e.preventDefault();
        const $button = $(this);
        const $inputField = $button.siblings(".sisf-quantity-input");
        const step = parseFloat($inputField.data("step")) || 1;
        const max = parseFloat($inputField.data("max"));
        const min = parseFloat($inputField.data("min")) || 1;
        let inputValue = parseFloat($inputField.val()) || min;

        inputValue = $button.hasClass("sisf-quantity-minus") ? Math.max(min, inputValue - step) : (Number.isNaN(max) ? inputValue + step : Math.min(max, inputValue + step));

        $inputField.val(inputValue).trigger("change");
    });

})(jQuery);