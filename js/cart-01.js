const cardWrapper = document.querySelector(".cart-wrapper");

// Добавление товара в корзину
window.addEventListener("click", function (event) {
    if (event.target.hasAttribute("data-cart")) {
        const item = event.target.closest('.item');

        // Сохранение товара в переменную
        const productInfo = {
            id: item.dataset.id,
            imgSrc: item.querySelector('.item_img').getAttribute('src'),
            title: item.querySelector('.item_title').innerText,

            size: item.querySelector('input[name="pizzaSize-id-'+item.dataset.id+'"]:checked')?.nextElementSibling.innerText || "Not selected",
            dough: item.querySelector('input[name="pizzaDough-id-' + item.dataset.id + '"]:checked')?.nextElementSibling.innerText || "Not selected",
            side: item.querySelector('input[name="pizzaSide-id-' + item.dataset.id + '"]:checked')?.nextElementSibling.innerText || "Not selected",

            price: parseFloat(item.querySelector('.item_price').innerText) || 0,
            count: parseInt(item.querySelector('[data-counter]').innerText) || 0,
        };




        // Проверяем существует ли товар уже в корзине
        const itemInCart = cardWrapper.querySelector(`[data-id="${productInfo.id}"]`);

        if (itemInCart) {
            // Увеличиваем количество в корзине
            const counter = itemInCart.querySelector('.pizza-count');
            counter.innerText = parseFloat(counter.innerHTML) + productInfo.count;
        } else {
            // Добавляем новый товар в корзину
            const cartItemHTML = `
            <div class="product-in-cart d-flex" data-id="${productInfo.id}">
                <div class="product-in-cart__img">
                    <img src="${productInfo.imgSrc}" alt=""/>
                </div>

                <div class="product-in-cart__details">
                    <div class="details-name mb-2">${productInfo.title}</div>
                    <div class="details-weight">610g</div>
                    <div class="details_more">Size: ${productInfo.size}, Dough: ${productInfo.dough}, Side: ${productInfo.side}</div>
                    <div class="plus-minus mt-2 mb-2">
                        <span class="minus-item btn-gray50" data-action="minus">-</span>
                        <span class="pizza-count" data-counter>${productInfo.count}</span>
                        <span class="plus-item btn-gray50" data-action="plus">+</span>
                    </div>
                    <div class="details-price">${productInfo.price} BYN</div>
                </div>

                <div class="x"><i class="fa-solid fa-x" data-action="delete"></i></div>
            </div>`;



            // Вставляем новый товар в корзину
            cardWrapper.insertAdjacentHTML("beforeend", cartItemHTML);
        }

        // Пересчитываем общую цену корзины
        calcCartPrice();

    }
    saveCart();
});

// Пересчитываем цену корзины
function calcCartPrice() {
    const cartWrapper = document.querySelector('.cart-wrapper');
    if (!cartWrapper) {
        return; // Выход из функции, если корзина пуста
    }

    const priceElements = cartWrapper.querySelectorAll('.details-price');
    const totalPriceEl = document.querySelector('.total-price');

    // считаем общую цену товаров в корзине
    let priceTotal = 0;

    priceElements.forEach(function (item) {
        const amountEl = item.closest('.product-in-cart').querySelector('[data-counter]');
        const price = parseFloat(item.innerText) || 0;
        const count = parseInt(amountEl.innerText) || 0;
        priceTotal += price * count;
    });

    totalPriceEl.innerText = priceTotal.toFixed(2); // Отображаем итоговую цену с двумя знаками после запятой
}


document.addEventListener("change", function (event) {
    const item = event.target.closest('.item');
    if (!item) return;

    let base_price = parseFloat(item.querySelector('.item_price').dataset.basePrice);

    // Обновление цены при выборе размера
    if (event.target.name.startsWith("pizzaSize-id-")) {
        const selectedSize = item.querySelector('input[name^="pizzaSize-id-"]:checked');
        const size_price = selectedSize ? parseFloat(selectedSize.dataset.price) || 0 : 0;
        item.dataset.selectedSizePrice = size_price; // Сохраняем выбранную цену размера

        const gramm_price = parseInt(selectedSize.dataset.gramm);
        item.querySelector('.gramm').innerText = `~ ${gramm_price} g`;
    }

    // Обновление цены при выборе бортика
    if (event.target.name.startsWith("pizzaSide-id-")) {
        const selectedSide = item.querySelector('input[name^="pizzaSide-id-"]:checked');
        const side_price = selectedSide ? parseFloat(selectedSide.dataset.priceside) || 0 : 0;
        item.dataset.selectedSidePrice = side_price; // Сохраняем выбранную цену бортика
    }

    // Получаем сохранённые значения цен или 0, если они не были выбраны
    const selectedSizePrice = parseFloat(item.dataset.selectedSizePrice) || 0;
    const selectedSidePrice = parseFloat(item.dataset.selectedSidePrice) || 0;

    // Итоговая цена
    const new_price = base_price + selectedSizePrice + selectedSidePrice;
    item.querySelector('.item_price').innerText = `${new_price.toFixed(1)} BYN`;

});


// cохраняем корзину в localstorage
function saveCart() {
    const cartItems = [];
    document.querySelectorAll('.product-in-cart').forEach(item => {
        cartItems.push({
            id: item.dataset.id,
            imgSrc: item.querySelector('img').getAttribute('src'),
            title: item.querySelector('.details-name').innerText,
            size: item.querySelector('.details_more').innerText.split(", ")[0].split(": ")[1],
            dough: item.querySelector('.details_more').innerText.split(", ")[1].split(": ")[1],
            side: item.querySelector('.details_more').innerText.split(", ")[2].split(": ")[1],
            price: parseFloat(item.querySelector('.details-price').innerText) || 0,
            count: parseInt(item.querySelector('[data-counter]').innerText) || 1
        });
    });

    localStorage.setItem("cart", JSON.stringify(cartItems));
}

// загрузка корзины из localstorage
function loadCart() {
    const savedCart = JSON.parse(localStorage.getItem("cart")) || [];

    savedCart.forEach(productInfo => {
        const cartItemHTML = `
            <div class="product-in-cart d-flex" data-id="${productInfo.id}">
                <div class="product-in-cart__img">
                    <img src="${productInfo.imgSrc}" alt=""/>
                </div>
                <div class="product-in-cart__details">
                    <div class="details-name mb-2">${productInfo.title}</div>
                    <div class="details-weight">610g</div>
                    <div class="details_more">Size: ${productInfo.size}, Dough: ${productInfo.dough}, Side: ${productInfo.side}</div>
                    <div class="plus-minus mt-2 mb-2">
                        <span class="minus-item btn-gray50" data-action="minus">-</span>
                        <span class="pizza-count" data-counter>${productInfo.count}</span>
                        <span class="plus-item btn-gray50" data-action="plus">+</span>
                    </div>
                    <div class="details-price">${productInfo.price} BYN</div>
                </div>
                <div class="x"><i class="fa-solid fa-x" data-action="delete"></i></div>
            </div>`;

        cardWrapper.insertAdjacentHTML("beforeend", cartItemHTML);
    });

    calcCartPrice(); // Обновляем итоговую сумму
}


// + - в товаре
window.addEventListener('click', function (event) {
    let counter;

    // Удаление товара из корзины
    if (event.target.dataset.action === "delete") {
        event.target.closest('.product-in-cart').remove();
        saveCart();
        calcCartPrice();
        return; // Выходим, чтобы не обрабатывать дальше
    }

    // Находим счётчик внутри блока plus-minus
    if (event.target.dataset.action === "plus" || event.target.dataset.action === "minus") {
        const plusMinus = event.target.closest('.plus-minus');
        if (!plusMinus) return; // Если не нашли родителя, выходим

        counter = plusMinus.querySelector(`[data-counter]`);
        if (!counter) return; // Если не нашли счётчик, выходим
    }

    // Уменьшение количества товара
    if (event.target.dataset.action === "minus") {
        if (parseInt(counter.innerText) > 1) {
            counter.innerText = parseInt(counter.innerText) - 1;
        } else if (event.target.closest('.cart-wrapper')) {
            // Если товар в корзине и его количество 1 — удаляем
            event.target.closest('.product-in-cart').remove();
        }
    }

    // Увеличение количества товара
    if (event.target.dataset.action === "plus") {
        counter.innerText = parseInt(counter.innerText) + 1;
    }

    // Сохранение изменений в localStorage
    saveCart();
    calcCartPrice();
});


window.addEventListener("DOMContentLoaded", loadCart);
