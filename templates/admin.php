<div class="pr-3">
    <h1>Надані відповіді на тести</h1>
    <div class="row mt-3 justify-content-between">
        <div class="col d-flex">
            <div class="users-dropdown" id="users-dropdown">
                <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button"
                        id="tests-dropdown-list"
                        data-toggle="dropdown" aria-expanded="false">
					<?php
					echo isset($current_user) ? $current_user->user_name : "Виберіть Користувача";
					?>
                </button>
                <div class="dropdown-menu" aria-labelledby="tests-dropdown-list">
					<?php
					if ( ! empty( $users ) ) {
						foreach ( $users as $user ) {
							if ( $user->is_active === 1 ) {
								?>
                                <a class="dropdown-item selectable-option" data-id="<?php echo $user->id ?>"
                                   href="<?php echo $current_page_url . "&userId=" . $user->id ?>">
									<?php echo $user->user_name ?>
                                </a>
								<?php
							}
						}
					} else {
						?>
                        <p>Поки що тести ніхто не здавав</p>
						<?php
					}
					?>
                </div>
            </div>
            <div class="tests-dropdown ml-2" id="tests-dropdown">
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
                            if($test->test_completed_counter > 0) {
					        ?>
                            <a class="dropdown-item selectable-option" data-id="<?php echo $test->test_id ?>"
                               href="<?php echo $current_page_url . (isset($current_user) ? "&userId=" . $current_user->id : "") . "&testId=" . $test->test_id ?>">
						        <span>
                                    <?php echo $test->test_name ?>
                                </span>
						        <span>
                                    <?php echo $test->test_completed_counter ?>
                                </span>
                            </a>
					        <?php
                            }
				        }
			        } else {
				        ?>
                        <p>Поки що тести ніхто не здавав</p>
				        <?php
			        }
			        ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="answer-list" id="accordionExample">
            <?php
                foreach ($answer_list as $answer_data_item) {
                    ?>

                    <div class="card answer-list__item">
                        <div class="card-header" id="heading<?php echo $answer_data_item->uuid ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-light btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?php echo $answer_data_item->uuid ?>" aria-expanded="true" aria-controls="collapse<?php echo $answer_data_item->uuid ?>">
                                    <span class="d-flex justify-content-between">
                                        <span><?php echo $answer_data_item->test_name ?></span>
                                        <div class="d-flex">
                                            <span class="d-flex">
                                                <span class="font-weight-bold">Користувач:</span>
                                                <span class="ml-2"><?php echo $answer_data_item->user_name ?></span>
                                            </span>
                                            <span class="d-flex ml-3">
                                                <span class="font-weight-bold">Завершено:</span>
                                                <span class="ml-2"><?php echo $answer_data_item->completion_date ?></span>
                                            </span>
                                        </div>
                                    </span>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse<?php echo $answer_data_item->uuid ?>" class="collapse" aria-labelledby="heading<?php echo $answer_data_item->uuid ?>" data-parent="#accordionExample">
                            <div class="card-body">
                                <table data-list-type="test" class="table table-striped table-editable table-sm mt-3">
                                    <thead class="thead-dark">
                                        <th scope="col">Питання</th>
                                        <th scope="col">Відповіді</th>
                                        <th scope="col">Бали</th>
                                    </thead>
                                    <tbody>

		                            <?php
                                    $total_points = 0;
		                            foreach ( $answer_data_item->answers_map as $answer_data ) {
				                            ?>
                                            <tr data-id="<?php echo $test->test_id; ?>">
                                                <td class="test-name">
                                                    <div class="editable" data-edit-type="text-link" data-editing="false">
                                                        <div>
								                            <?php echo $answer_data->question; ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="test-description">
                                                    <div class="editable" data-edit-type="text" data-editing="false">
							                            <?php
                                                            $answer_text_list = [];
                                                            foreach ($answer_data->answer as $answer_details) {
                                                                $answer_text_list[] = "$answer_details->option_text ($answer_details->option_value)";
                                                            }

                                                            echo implode(', ', $answer_text_list);
                                                        ?>
                                                    </div>
                                                </td>
                                                <td>
	                                                <?php
                                                        $answer_value_list = [];
                                                        foreach ($answer_data->answer as $answer_details) {
	                                                        $answer_value_list[] = $answer_details->option_value;
                                                        }
                                                        $total_by_row = array_sum($answer_value_list);
                                                        $total_points += $total_by_row;

                                                        echo array_sum($answer_value_list);
	                                                ?>
                                                </td>
                                            </tr>
				                            <?php
		                            }
		                            ?>
                                    <tr>
                                        <th scope="row" colspan="2">Всього</th>
                                        <td><?php echo $total_points ?></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <?php
                }
            ?>
        </div>
    </div>
</div>
