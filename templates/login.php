<main>
    <?= $categories_list_templ; ?>

    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="login.php" method="post">
        <?= csrfField(); ?>
        <h2>Вход</h2>
        <p>YetiCave создан в учебных целях и опубликован как портфолио. <br>Это не торговая площадка и не аукцион: лоты, ставки и контакты не создают реальных сделок.</p>
        <?php if (!empty($errors['form'])): ?>
            <span class="form__error form__error--bottom"><?= e($errors['form']) ?></span>
        <?php endif; ?>
        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>">
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= e($_POST['email'] ?? '') ?>">
            <span class="form__error"><?= e($errors['email'] ?? '') ?></span>
        </div>
        <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= e($_POST['password'] ?? '') ?>">
            <span class="form__error"><?= e($errors['password'] ?? '') ?></span>
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
</main>
