<main>
    <?= $categories_list_templ; ?>

    <section class="lot-item container cookies">
        <h2>Файлы cookie</h2>
        <p>
            YetiCave сохраняет только технические cookie. Они нужны, чтобы сайт держал вход
            и проверял, что формы отправлены с этого сайта. Аналитических и рекламных cookie нет.
            Содержимое этих cookie третьим лицам не передаётся.
        </p>
        <table class="cookies__table">
            <thead>
                <tr>
                    <th>Имя</th>
                    <th>Зачем нужна</th>
                    <th>Срок</th>
                    <th>Кому доступна</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PHPSESSID</td>
                    <td>Сессия входа и защита форм от подделки запроса (CSRF). Без неё аукцион не работает. Скрипты страницы её не читают.</td>
                    <td>До закрытия браузера</td>
                    <td>Только YetiCave</td>
                </tr>
                <tr>
                    <td>yc_cookie_ok</td>
                    <td>Отметка, что уведомление о технических cookie уже показано.</td>
                    <td>1 год</td>
                    <td>Только YetiCave</td>
                </tr>
            </tbody>
        </table>
    </section>
</main>
