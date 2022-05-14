<div>
	<?php
	if ( empty( $test ) ) {
		?>
        <div>Тест в розробці...</div>
		<?php
	} else {
		?>
        <div class="user-info-title dNone">
            <a class="user-info-title__change" href="#">Змінити користувача</a>
            <h6>Привіт <span id="test-user-name"></span></h6>
        </div>
        <h3 class="test-title">
			<?php echo $test['test_name'] ?>
            <span class="test-questions-count">(Кількість питань: <?php echo count( $questions ); ?>)</span>
        </h3>
        <p class="test-description">
			<?php echo $test['test_description'] ?>
        </p>
        <form method="POST" id="test-form" data-test-id="<?php echo $test['test_id'] ?>">
            <div id="form_error"></div>
            <div class="question-list">
				<?php
				if ( ! empty( $questions ) ) {
					$counter = 0;
					foreach ( $questions as $question ) {
						$counter ++;
                        $current_answers = array_key_exists($question['id'], $answers) ? $answers[$question['id']] : null;
						?>
                        <div class="question-list__item"
                             data-type="<?php echo $question['selection_type'] == 'single' ? "radio" : "checkbox" ?>"
                             id="question_<?php echo $question['id'] ?>"
                        >
                            <p class="question-list__item-title"><?php echo $counter . ". " . $question['question_text'] ?></p>
                            <p class="question-list__item-description"><?php echo $question['question_description'] ?></p>


                            <div class="question-list__options">
								<?php
								foreach ( $question['options'] as $option ) {
                                    $is_selected = false;
                                    if(isset($current_answers)) {
	                                    $is_selected = in_array($option->id, $current_answers);
                                    }
									?>
                                    <div class="form-check">
                                        <input class="form-check-input <?php echo $question['selection_type'] == 'single' ? "" : "require-one" ?>"
                                               type="<?php echo $question['selection_type'] == 'single' ? "radio" : "checkbox" ?>"
                                               name="question_<?php echo $question['id'] ?>"
                                               id="option_<?php echo $question['id'] . "_" . $option->id ?>"
                                               value="<?php echo $option->id ?>"
                                               <?php echo $is_selected ? "checked" : "" ?>
                                        >
                                        <label class="form-check-label" for="<?php echo $question['id'] ?>">
											<?php echo $option->text ?>
                                        </label>
                                    </div>
									<?php
								}
								?>
                            </div>


                        </div>
						<?php
					}
				} else {
					?>
                    <p>Питання для тесту поки що відсутні...</p>
					<?php
				}
				?>
                <button type="submit" class="test-submit">Завершити і відправити в обробку</button>
            </div>
        </form>
		<?php
	}
	?>
</div>
<div id="login-form" class="modal">
    <div class="user-submit-dialog__title">Привіт</div>
    <form id="user-submit-form" class="user-submit-dialog">
        <div class="form-input-container">
            <input type="text" id="user-name" name="user-name" placeholder="Вкажіть ваше ім'я">
        </div>
        <div class="form-input-container">
            <input type="text" id="user-phone" name="user-phone" placeholder="Вкажіть ваш телефон">
        </div>
        <div class="form-input-container">
            <input type="text" id="user-email" name="user-email" placeholder="Вкажіть ваш e-mail">
        </div>
        <div class="flex">
            <button type="submit" class="dNone" id="submit-user">Підтвердити</button>
            <button type="button" class="dNone" id="cancel-submit-user">Закрити</button>
        </div>
    </form>
</div>