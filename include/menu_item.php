<li>
    <a class="main-menu__item <?= ($current_page == $item['name']) ? 'active"' : '" href=' . '"' . $item['path'] . '"' ?>>
        <?= $item['name'] ?>
    </a>
</li>