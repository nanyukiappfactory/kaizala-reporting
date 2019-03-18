<!-- Modal -->
<?php
$tr_actions_responses = "";
foreach ($action_responses as $key => $action_response) {
    /**
     * So when you are going to use javascript date object timestamp with php date object you should divide timestamp of javascript by 1000 and use it in php
     * eg.
     * $date = intval(1552652696929/1000);
     * echo date('d M Y H:i', $date);
     */

    if ($action_response->action_question_type == 'Location') {
        $answer = $action_response->response_location;
    } else if ($action_response->action_question_type == 'DateTime') {
        $str_date = $action_response->action_answer;
        $num_date = $str_date + 0;
        $date = intval($num_date / 1000);
        // var_dump($date);die();
        $answer = date('d M Y H:i', $date);
    } else {
        $answer = $action_response->action_answer;
    }
    if (empty($answer)) {
        $answer = "No answer";
    }

    $tr_actions_responses .= "
		<tr>
			<td>" . $action_response->action_question_type . "</td>
			<td>" . $action_response->action_question . "</td>
			<td>" . $answer . "</td>
		</tr>
	";
}
?>
<div class="modal fade" id="singleResponse<?php echo $response_id; ?>" tabindex="-1" role="dialog"
    aria-labelledby="singleResponseLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="singleResponseLabel">
                    <?php echo $responder_name; ?> Responses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">QuestionType</th>
                            <th scope="col">Questions</th>
                            <th scope="col">Answers</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tr_actions_responses; ?>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>