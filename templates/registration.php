<main>
    <?= $categories_list_templ; ?>

    <form class="form container <?= !empty($errors) ? 'form--invalid' : ''; ?>" action="registration.php" method="post" autocomplete="off">
      <?= csrfField(); ?>
      <h2>Регистрация нового аккаунта</h2>

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
      <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= e($_POST['name'] ?? '') ?>">
        <span class="form__error"><?= e($errors['name'] ?? '') ?></span>
      </div>
      <div class="form__item <?= isset($errors['message']) ? 'form__item--invalid' : '' ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= e($_POST['message'] ?? '') ?></textarea>
        <span class="form__error"><?= e($errors['message'] ?? '') ?></span>
      </div>
      <div class="form__item form__consent <?= isset($errors['pd_consent']) ? 'form__item--invalid' : '' ?>">
        <input id="pd_consent" type="checkbox" name="pd_consent" value="1" <?= !empty($_POST['pd_consent']) ? 'checked' : '' ?>>
        <label for="pd_consent">
          Я даю <a class="text-link" href="consent.php">согласие на обработку персональных данных</a>
          (<a class="text-link" href="privacy.php">политика</a>)
        </label>
        <span class="form__error"><?= e($errors['pd_consent'] ?? '') ?></span>
      </div>
      <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
      <p>YetiCave создан в учебных целях и опубликован как портфолио. <br>Это не торговая площадка и не аукцион: лоты, ставки и контакты не создают реальных сделок.</p>
      <button type="submit" class="button">Зарегистрироваться</button>
      <a class="text-link" href="login.php">Уже есть аккаунт</a>
    </form>
</main>
