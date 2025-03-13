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
            <span id="paginate-page" class="mr-2">Страница: </span>
            <div id="pagination" class="pagination"></div>
        </div>
    </div>

</div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {

        let url = $(location).attr('pathname');
         let decodedUrl = decodeURIComponent(url);

        let search = decodeURIComponent($(location).attr('search'))


        var parts = decodedUrl.split('/');
        var allParts = parts.join('/');
        let currentPage = 1;
        let sortBy = 'name';
        let order = 'asc';

            function fetchProducts(parts, item = null) {
                    const params = {
                        sort_by: sortBy,
                        order: order,
                        page: currentPage,
                    };

                    let fullPart;
                    if(item != null)
                    {
                        fullPart = parts + item;
                    } else {
                        fullPart = parts;
                    }



                    $.get('/api/products' + fullPart , params,  function(data) {

                      const productList = $('#product-list');
                        productList.empty();
                        $.each(data.data, function(index, product) {

                            const listItem = $('<a href="">').text(`${product.name} - ${product.price} руб.`).attr('href', '/product/' + product.id).append('<br>');
                            productList.append(listItem);
                        });

                        //Обновляем пагинацию с номером страницы
                        if(data.last_page >= 2) {
                            updatePagination(data, fullPart, item);
                        } else {
                            const numberPage = $('#paginate-page');
                            numberPage.remove();
                        }
                    });
                }

                function updatePagination(data, fullPart, item) {
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
                                fetchProducts(fullPart, item);
                            }
                        });
                        pagination.append(pageLink);
                    }
                }

                $('#sort').on('change', function() {
                    sortBy = $(this).val();
                    currentPage = 1; // Сбрасываем страницу на первую при изменении сортировки
                    fetchProducts(allParts, search);
                });

                $('#order').on('change', function() {
                    order = $(this).val();
                    currentPage = 1; // Сбрасываем страницу на первую при изменении порядка
                    fetchProducts(allParts, search);
                });
                fetchProducts(allParts, search);

        });

    </script>



