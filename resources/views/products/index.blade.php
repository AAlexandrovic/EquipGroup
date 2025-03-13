<style>
    .pagination .link {
        padding: 0.5rem 0.75rem; /* Параметры отступов */
        margin: 0 0.2rem; /* Отступы между ссылками */
    }
</style>
        <div class="container">
            <div>
                <label for="sort">Сортировать по:</label>
                <select id="sort" onchange="fetchProducts()">
                    <option value="name">Название</option>
                    <option value="price">Цена</option>
                </select>

                <select id="order" onchange="fetchProducts()">
                    <option value="asc">По возрастанию</option>
                    <option value="desc">По убыванию</option>
                </select>
            </div>

            <ul id="product-list"></ul>

            <div>
                <div class="d-flex align-items-center">
                    <span class="mr-2">Страница: </span>
                    <div id="pagination" class="pagination"></div>
                </div>
            </div>

        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            let currentPage = 1;
            let sortBy = 'name';
            let order = 'asc';

            function fetchProducts() {
                const params = {
                    sort_by: sortBy,
                    order: order,
                    page: currentPage,
                };

                $.get('/api/products', params, function(data) {

                    const productList = $('#product-list');
                    productList.empty();
                    $.each(data.data, function(index, product) {
                        const listItem = $('<a href="">').text(`${product.name} - ${product.price} руб.`).attr('href', '/product/' + product.id).append('<br>');
                        productList.append(listItem);
                    });

                    // Обновляем пагинацию с номером страницы
                    updatePagination(data);
                });
            }

            function updatePagination(data) {
                const pagination = $('#pagination');
                pagination.empty();

                // Показываем номера страниц
                for (let i = 1; i <= data.last_page; i++) {
                    const pageLink = $('<a>', {
                        text: i,
                        href: '#',
                        class: (i === currentPage) ? 'active link' : 'link',
                        click: function(e) {
                            e.preventDefault();
                            currentPage = i;
                            fetchProducts();
                        }
                    });
                    pagination.append(pageLink).append(' ');
                }
            }

            $('#sort').on('change', function() {
                sortBy = $(this).val();
                currentPage = 1; // Сбрасываем страницу на первую при изменении сортировки
                fetchProducts();
            });

            $('#order').on('change', function() {
                order = $(this).val();
                currentPage = 1; // Сбрасываем страницу на первую при изменении порядка
                fetchProducts();
            });

            // Изначальный вызов для загрузки продуктов
            fetchProducts();
            });
        </script>
