<div class="pr-3">
    <h1>Керування тестами</h1>
    <div>
        <p>Тут можна буде вибрати тест з випадаючого списку і переглянути список питань по ньому. Також буде рядок для
            асинхронного пошуку питань</p>
        <p><strong>Для редагування</strong> - клікни на іконці олівця, поля, яке хочеш редагувати.</p>
        <p><strong>Щоб зберегти</strong> відредаговане поле, натисни Enter.</p>
        <p><strong>Щоб відмінити редагування</strong> до того, як збергі їх, натисни Esc</p>
        <div class="row mt-3 justify-content-between">
            <div class="col">
                <div class="tests-dropdown" id="tests-dropdown">
                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                            id="tests-dropdown-list"
                            data-toggle="dropdown" aria-expanded="false">
						<?php
						echo $current_test_id ? $current_test_name : "Виберіть тест";
						?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="tests-dropdown-list">
						<?php
						if ( ! empty( $tests ) ) {
							foreach ( $tests as $test ) {
								?>
                                <a class="dropdown-item selectable-option" data-id="<?php echo $test->test_id ?>"
                                   href="<?php echo $current_page_url . "&testId=" . $test->test_id ?>">
									<?php echo $test->test_name ?>
                                </a>
								<?php
							}
						} else {
							?>
                            <a class="dropdown-item"
                               href="<?php echo admin_url( 'admin.php?page=yaroslaw_tests_settings' ) ?>">Додати перший
                                тест</a>
							<?php
						}
						?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row <?php echo is_numeric( $current_test_id ) ? "" : "d-none" ?>" id="add-question-form">
            <div class="col-12">
                <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" id="question-form">
                    <input type="hidden" name="action" value="save_question">
                    <input type="hidden" name="test_id" value="<?php echo $current_test_id; ?>">
                    <input type="hidden" name="question_count" value="<?php echo count( $question_list ); ?>">
                    <div class="form-group col-sm-12">
                        <label class="col-form-label" for="new-question_name">Текст питання</label>
                        <div>
                            <input type="text" name="question_text" class="form-control" id="new-question_name" required>
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-form-label" for="new-question_name">Ідентифікатор питання</label>
                        <div>
                            <input type="text" name="question_group" class="form-control" id="new-question_group">
                        </div>
                    </div>
                    <div class="form-group col-sm-12">
                        <label class="col-form-label" for="new-question_description">Опис питання</label>
                        <div>
                            <textarea class="form-control" id="new-question_description" type="textarea" name="question_description"
                              rows="3"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
						<?php
						foreach ( $question_types as $question_type ) {
							?>

                            <div class="form-check">
                                <input type="radio"
                                       name="question_type"
                                       id="<?php echo "type" . $question_type['id'] ?>"
                                       value="<?php echo $question_type['id']; ?>" required>
                                <label class="form-check-label" for="<?php echo "type" . $question_type['id'] ?>">
									<?php echo $question_type['type_name']; ?>
                                </label>
                            </div>

							<?php
						}
						?>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Зберегти питання</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table data-list-type="question" data-test-id="<?php echo $current_test_id; ?>"
                       class="table table-editable table-striped table-sm mt-3 <?php echo is_numeric( $current_test_id ) ? "" : "d-none" ?>"
                       id="questions-list" data-editing="false">
                    <thead class="thead-dark">
                    <th scope="col">Питання</th>
                    <th scope="col">Опис питання</th>
                    <th scope="col">Група питання</th>
                    <th scope="col">Тип відповіді</th>
                    <th scope="col">Варіанти відповіді</th>
                    <th scope="col">Керування питанням</th>
                    </thead>
                    <tbody>
					<?php
					if ( empty( $question_list ) ) {
						?>
                        <tr class="no-data">
                            <td colspan="4">
                                Питання відсутні
                            </td>
                        </tr>
						<?php
					} else {
						foreach ( $question_list as $question ) {
							?>
                            <tr data-id="<?php echo $question['id'] ?>">
                                <td class="question-name">
                                    <div class="editable" data-edit-type="text" data-editing="false">
										<?php echo $question['question_text'] ?>
                                    </div>
                                    <span class="edit-handler material-icons">
                                            edit
                                        </span>
                                </td>
                                <td class="question-description">
                                    <div class="editable" data-edit-type="text" data-editing="false">
										<?php echo $question['question_description'] ?>
                                    </div>
                                    <span class="edit-handler material-icons">
                                            edit
                                        </span>
                                </td>
                                <td class="question-group">
                                    <div class="editable" data-edit-type="text" data-editing="false">
			                            <?php echo $question['question_group'] ?>
                                    </div>
                                    <span class="edit-handler material-icons">
                                    edit
                                </span>
                                </td>
                                <td class="question-type">
                                    <div class="editable" data-edit-type="options-question-types" data-editing="false">
										<?php echo $question['type_name'] ?>
                                    </div>
                                    <span class="edit-handler material-icons">
                                    edit
                                </span>
                                </td>
                                <td class="options">
                                    <div class="card answers py-0 px-2 flex-row mt-0">
                                        <div class="card-body p-1 d-flex">
											<?php
											$question_options = json_decode( $question['options'] );
											if ( empty( $question_options ) ) {
												echo "Варіанти відповіді відсутні";
											} else {
												foreach ( $question_options as $option ) {
													?>
                                                    <div class="single-answer d-inline-flex mr-2"
                                                         data-id="<?php echo $option->id; ?>">
                                                        <span class="badge badge-primary option-text rounded-left"><?php echo $option->text; ?></span>
                                                        <span class="badge badge-primary rounded-right remove-answer">&times;</span>
                                                    </div>
													<?php
												}
											}
											?>

                                        </div>
                                        <button type="button"
                                                class="btn btn-sm no-border btn-outline-primary d-inline-flex align-items-center p-0 rounded-circle align-self-center add-option"
                                        >
                                        <span class="material-icons">
                                            add
                                        </span>
                                        </button>
                                    </div>
                                </td>
                                <td class="actions">
                                    <div class="d-flex form-inline">
                                        <div class="justify-content-start form-check">
                                            <input class="form-check-input question-activation" type="checkbox" value=""
                                                   id="question-<?php echo $question['id'] ?>"
												<?php echo $question['is_active'] == 1 ? "checked" : "" ?>>
                                            <label class="form-check-label"
                                                   for="checkbox_<?php echo $question['id']; ?>">
                                                Питання активне
                                            </label>
                                        </div>
                                        <div class="ml-auto question-actions">
                                            <button class="btn btn-sm no-border btn-outline-danger remove-question">
                                            <span class="material-icons">
                                                delete
                                            </span>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                            </tr>
							<?php
						}
					}
					?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="option-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="option-modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="option-form">
                        <div class="form-row">
                            <div class="col">
                                <label class="col-form-label-sm" for="form-option-name">Варіант</label>
                                <input type="text" class="form-control " id="form-option-name">
                            </div>

                            <div class="col-2">
                                <label class="col-form-label-sm" for="form-option-value">Вага</label>
                                <input type="number" class="form-control" id="form-option-value">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary py-1 px-2" data-dismiss="modal">Відміна
                    </button>
                    <button type="button" class="btn btn-sm btn-primary py-1 px-2" id="save-option">Зберегти</button>
                </div>
            </div>
        </div>
    </div>

</div>