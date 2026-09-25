/* cart */
document.addEventListener('DOMContentLoaded', function () {
  const cartIcon = document.querySelector('.cart-wrapper');
  const cartDropdown = cartIcon?.querySelector('.group-hover\\:block');

  if (cartIcon && cartDropdown) {
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
  }
});

/* mobile menu */
document.addEventListener("DOMContentLoaded", function () {
  const hamburgerBtn = document.getElementById('hamburger');
  const mobileMenu = document.querySelector('.mobile-menu');

  if (hamburgerBtn && mobileMenu) {
    hamburgerBtn.addEventListener('click', function () {
      mobileMenu.classList.toggle('hidden');
    });
  }
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
const searchIcon = document.getElementById('search-icon');
const searchField = document.getElementById('search-field');

if (searchIcon && searchField) {
  searchIcon.addEventListener('click', function() {
    if (searchField.classList.contains('hidden')) {
        searchField.classList.remove('hidden');
        searchField.classList.add('search-slide-down');
    } else {
        searchField.classList.add('hidden');
        searchField.classList.remove('search-slide-down');
    }
  });
}

function toggleDropdown(id, show) {
  const dropdown = document.getElementById(id);
  if (show) {
      dropdown.classList.remove('hidden');
  } else {
      dropdown.classList.add('hidden');
  }
}

function changeImage(element) {
  var mainImage = document.getElementById('main-image');
  mainImage.src = element.getAttribute('data-full');
}

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

/* Shopping cart shared by the product pages and cart page. */
document.addEventListener('DOMContentLoaded', function () {
  const storageKey = 'computer-store-cart';
  const currency = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });
  const readCart = () => {
    try {
      const cart = JSON.parse(localStorage.getItem(storageKey) || '[]');
      return Array.isArray(cart) ? cart : [];
    } catch {
      return [];
    }
  };
  const saveCart = cart => {
    localStorage.setItem(storageKey, JSON.stringify(cart));
    renderCart(cart);
  };
  const money = amount => currency.format(amount);

  function renderCart(cart) {
    const tbody = document.getElementById('cart-items');
    if (tbody) {
      tbody.replaceChildren();
      cart.forEach(item => {
        const row = document.createElement('tr');
        row.className = 'pb-4 border-b border-gray-line';
        row.innerHTML = `
          <td class="px-1 py-4"><div class="flex items-center flex-col sm:flex-row text-center sm:text-left">
            <img class="h-16 w-16 md:h-24 md:w-24 sm:mr-8 mb-4 sm:mb-0 object-cover" src="${escapeAttribute(item.image)}" alt="${escapeAttribute(item.name)}">
            <p class="text-sm md:text-base md:font-semibold">${escapeHtml(item.name)}</p>
          </div></td>
          <td class="px-1 py-4 text-center">${money(item.price)}</td>
          <td class="px-1 py-4 text-center"><div class="flex items-center justify-center">
            <button type="button" data-cart-change="-1" data-product-id="${escapeAttribute(item.id)}" class="cart-decrement border border-primary bg-primary text-white hover:bg-transparent hover:text-primary rounded-full w-10 h-10 flex items-center justify-center">-</button>
            <p class="quantity text-center w-8">${item.quantity}</p>
            <button type="button" data-cart-change="1" data-product-id="${escapeAttribute(item.id)}" class="cart-increment border border-primary bg-primary text-white hover:bg-transparent hover:text-primary rounded-full w-10 h-10 flex items-center justify-center">+</button>
          </div></td>
          <td class="px-1 py-4 text-right">${money(item.price * item.quantity)}</td>`;
        tbody.appendChild(row);
      });

      const subtotal = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
      const taxes = subtotal * 0.1;
      document.getElementById('cart-subtotal').textContent = money(subtotal);
      document.getElementById('cart-taxes').textContent = money(taxes);
      document.getElementById('cart-total').textContent = money(subtotal + taxes);
      const hasItems = cart.length > 0;
      document.querySelector('.empty-cart-state').classList.toggle('hidden', hasItems);
      document.querySelector('.empty-cart-state').classList.toggle('flex', !hasItems);
      document.getElementById('cart-table').classList.toggle('hidden', !hasItems);
      document.getElementById('cart-actions').classList.toggle('hidden', !hasItems);
    }

    document.querySelectorAll('[data-cart-preview]').forEach(preview => {
      preview.replaceChildren();
      if (!cart.length) {
        const empty = document.createElement('p');
        empty.className = 'text-sm text-gray-500';
        empty.textContent = 'Your cart is empty.';
        preview.appendChild(empty);
      }
      cart.slice(0, 3).forEach(item => {
        const line = document.createElement('div');
        line.className = 'flex items-center justify-between pb-3 border-b border-gray-line';
        line.innerHTML = `<div class="flex items-center"><img src="${escapeAttribute(item.image)}" alt="${escapeAttribute(item.name)}" class="h-12 w-12 object-cover rounded mr-2"><div><p class="font-semibold">${escapeHtml(item.name)}</p><p class="text-sm">Quantity: ${item.quantity}</p></div></div><p class="font-semibold">${money(item.price * item.quantity)}</p>`;
        preview.appendChild(line);
      });
    });
    document.querySelectorAll('.cart-wrapper').forEach(wrapper => {
      const iconLink = wrapper.querySelector('a[href*="cart"]');
      if (!iconLink) return;
      let badge = iconLink.querySelector('[data-cart-count]');
      const count = cart.reduce((sum, item) => sum + item.quantity, 0);
      if (count && !badge) {
        badge = document.createElement('span');
        badge.dataset.cartCount = '';
        badge.className = 'absolute -top-2 -right-2 rounded-full bg-primary px-2 py-1 text-xs text-white';
        iconLink.classList.add('relative');
        iconLink.appendChild(badge);
      }
      if (badge) {
        badge.textContent = count;
        badge.classList.toggle('hidden', count === 0);
      }
    });
  }

  function escapeHtml(value) {
    return String(value).replace(/[&<>"']/g, character => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[character]);
  }

  function escapeAttribute(value) {
    return escapeHtml(value);
  }

  document.addEventListener('click', function (event) {
    const addButton = event.target.closest('button');
    if (addButton && addButton.textContent.trim().replace(/\s+/g, ' ') === 'Add to Cart') {
      const productCard = addButton.closest('.bg-white.p-3');
      const name = productCard?.querySelector('a')?.textContent.trim();
      const priceText = productCard?.querySelector('span.text-lg.font-bold')?.textContent || '';
      const image = productCard?.querySelector('img')?.getAttribute('src');
      const price = Number.parseFloat(priceText.replace(/[^\d.]/g, ''));
      if (!name || !image || !Number.isFinite(price)) return;
      event.preventDefault();
      const cart = readCart();
      const id = `${name.toLowerCase()}|${image}`;
      const existing = cart.find(item => item.id === id);
      if (existing) existing.quantity += 1;
      else cart.push({ id, name, image, price, quantity: 1 });
      saveCart(cart);
      addButton.textContent = 'Added to Cart';
      window.setTimeout(() => { addButton.textContent = 'Add to Cart'; }, 1200);
      return;
    }

    const quantityButton = event.target.closest('[data-cart-change]');
    if (quantityButton) {
      const cart = readCart();
      const item = cart.find(product => product.id === quantityButton.dataset.productId);
      if (item) item.quantity += Number(quantityButton.dataset.cartChange);
      saveCart(cart.filter(product => product.quantity > 0));
      return;
    }

    if (event.target.closest('#empty-cart')) saveCart([]);
  });

  window.addEventListener('storage', event => {
    if (event.key === storageKey) renderCart(readCart());
  });
  renderCart(readCart());
});
