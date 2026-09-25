/* cart */
document.addEventListener('DOMContentLoaded', function () {
  const cartIcon = document.querySelector('.cart-wrapper');
  const cartDropdown = cartIcon.querySelector('.group-hover\\:block');

  cartIcon.addEventListener('mouseenter', function () {
      clearTimeout(cartIcon.__timer);
      cartDropdown.classList.remove('hidden');
  });

  cartIcon.addEventListener('mouseleave', function () {
      cartIcon.__timer = setTimeout(() => {
          cartDropdown.classList.add('hidden');
      }, 1300);
  });

  cartDropdown.addEventListener('mouseenter', function () {
      clearTimeout(cartIcon.__timer);
  });

  cartDropdown.addEventListener('mouseleave', function () {
      cartIcon.__timer = setTimeout(() => {
          cartDropdown.classList.add('hidden');
      }, 1300);
  });
});

/* mobile menu */
document.addEventListener("DOMContentLoaded", function () {
  const hamburgerBtn = document.getElementById('hamburger');
  const mobileMenu = document.querySelector('.mobile-menu');

  hamburgerBtn.addEventListener('click', function () {
    mobileMenu.classList.toggle('hidden');
  });
});

/* swiper slider */
if (typeof Swiper !== 'undefined') {
  var swiper = new Swiper('.swiper', {
    slidesPerView: 2,
    loop: true,
    autoplay: {
        delay: 3000,
    },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    breakpoints: {
        1024: {
            slidesPerView: 6,
        },
    },
  });

  var swiper = new Swiper('.main-slider', {
    slidesPerView: 1,
    loop: true,
    autoplay: {
      delay: 5000,
  },
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
}

/* search icon show/hide */
document.getElementById('search-icon').addEventListener('click', function() {
  var searchField = document.getElementById('search-field');
  if (searchField.classList.contains('hidden')) {
      searchField.classList.remove('hidden');
      searchField.classList.add('search-slide-down');
  } else {
      searchField.classList.add('hidden');
      searchField.classList.remove('search-slide-down');
  }
});

function toggleDropdown(id, show) {
  const dropdown = document.getElementById(id);
  if (show) {
      dropdown.classList.remove('hidden');
  } else {
      dropdown.classList.add('hidden');
  }
}

function changeImage(element) {
  var mainImage = document.getElementById('srcImg');
  if (!mainImage) {
    return;
  }

  mainImage.src = element.getAttribute('data-full');
  mainImage.dispatchEvent(new Event('zoomimagechange'));
}

/* product image zoom */
document.addEventListener('DOMContentLoaded', function () {
  const box = document.getElementById('zoomBox');
  const img = document.getElementById('srcImg');
  const lens = document.getElementById('lens');
  const result = document.getElementById('result');

  if (!box || !img || !lens || !result) {
    return;
  }

  function initZoom() {
    if (!img.naturalWidth || !img.naturalHeight) {
      return;
    }

    const imageRatio = img.naturalWidth / img.naturalHeight;
    box.style.aspectRatio = imageRatio;
    result.style.aspectRatio = imageRatio;
    result.style.backgroundImage = `url("${img.currentSrc || img.src}")`;
    result.style.backgroundSize = '200% 200%';
    result.style.backgroundPosition = '0% 0%';
  }

  function moveZoom(event) {
    const rect = box.getBoundingClientRect();
    const lensWidth = lens.offsetWidth;
    const lensHeight = lens.offsetHeight;
    let x = event.clientX - rect.left;
    let y = event.clientY - rect.top;

    x = Math.max(lensWidth / 2, Math.min(x, rect.width - lensWidth / 2));
    y = Math.max(lensHeight / 2, Math.min(y, rect.height - lensHeight / 2));

    lens.style.left = `${x - lensWidth / 2}px`;
    lens.style.top = `${y - lensHeight / 2}px`;

    const ratioX = (x - lensWidth / 2) / (rect.width - lensWidth);
    const ratioY = (y - lensHeight / 2) / (rect.height - lensHeight);
    result.style.backgroundPosition = `${ratioX * 100}% ${ratioY * 100}%`;
    result.classList.remove('opacity-0');
    result.classList.add('opacity-100');
  }

  img.addEventListener('load', initZoom);
  img.addEventListener('zoomimagechange', initZoom);
  box.addEventListener('mousemove', moveZoom);
  box.addEventListener('mouseleave', function () {
    result.classList.add('opacity-0');
    result.classList.remove('opacity-100');
  });

  initZoom();
});

/* single page product count */
document.addEventListener('DOMContentLoaded', function () {
    const decreaseButton = document.getElementById('decrease');
    const increaseButton = document.getElementById('increase');
    const quantityInput = document.getElementById('quantity');
  
    if (decreaseButton && increaseButton && quantityInput) {
        decreaseButton.addEventListener('click', function () {
            let quantity = parseInt(quantityInput.value);
            if (quantity > 1) {
                quantity -= 1;
                quantityInput.value = quantity;
            }
            updateButtons();
        });
  
        increaseButton.addEventListener('click', function () {
            let quantity = parseInt(quantityInput.value);
            quantity += 1;
            quantityInput.value = quantity;
            updateButtons();
        });
  
        function updateButtons() {
            if (parseInt(quantityInput.value) === 1) {
                decreaseButton.setAttribute('disabled', true);
            } else {
                decreaseButton.removeAttribute('disabled');
            }
        }
    }
  });

/* single product tabs */
document.addEventListener('DOMContentLoaded', function () {
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    if (tabs.length > 0 && contents.length > 0) {
        tabs.forEach(tab => {
            tab.addEventListener('click', function () {
                tabs.forEach(t => {
                    t.classList.remove('active');
                    t.setAttribute('aria-selected', 'false');
                });
                contents.forEach(c => c.classList.add('hidden'));

                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                document.querySelector(`#${this.id.replace('-tab', '-content')}`).classList.remove('hidden');
            });
        });

        tabs[0].click();
    }
});


/* shop page filter show/hide */
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('products-toggle-filters');
    const filters = document.getElementById('filters');

    if (toggleButton && filters) {
        toggleButton.addEventListener('click', function() {
            if (filters.classList.contains('hidden')) {
                filters.classList.remove('hidden');
                this.textContent = 'Hide Filters';
            } else {
                filters.classList.add('hidden');
                this.textContent = 'Show Filters';
            }
        });
    }
});

/* shop page filter*/
document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.querySelector('select');
    const arrowDown = document.getElementById('arrow-down');
    const arrowUp = document.getElementById('arrow-up');

    if (selectElement && arrowDown && arrowUp) {
        selectElement.addEventListener('click', function () {
            arrowDown.classList.toggle('hidden');
            arrowUp.classList.toggle('hidden');
        });
    }
});

/* cart page */
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.cart-increment').forEach(button => {
      button.addEventListener('click', function () {
          let quantityElement = this.previousElementSibling;
          let quantity = parseInt(quantityElement.textContent, 10);
          quantityElement.textContent = quantity + 1;
      });
  });

  document.querySelectorAll('.cart-decrement').forEach(button => {
      button.addEventListener('click', function () {
          let quantityElement = this.nextElementSibling;
          let quantity = parseInt(quantityElement.textContent, 10);
          if (quantity > 1) {
              quantityElement.textContent = quantity - 1;
          }
      });
  });
});