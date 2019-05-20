<!-- Modal -->
<?php
$tr_actions_responses = "";
$count = 1;
foreach ($action_responses as $key => $value) {
    if($response_id == $value->unique_response_id){
        $question_type = $value->action_card_question_type;
        $action_answer = $value->action_answer;
        $action_question = $value->action_card_question;
        $location = $value->action_card_question_location;
        /**
         * So when you are going to use javascript date object timestamp with php date object you should divide timestamp of javascript by 1000 and use it in php
         * eg.
         * $date = intval(1552652696929/1000);
         * echo date('d M Y H:i', $date);
         */

        if ($question_type == 'Location') {
            $answer = $location;
        } else if ($question_type == 'DateTime') {
            $str_date = $action_answer;
            $num_date = $str_date + 0;
            $date = intval($num_date / 1000);
            $answer = date('d M Y H:i', $date);
        } else {
            $answer = $action_answer;
        }
        if (empty($answer)) {
            $answer = "No answer";
        }

        $tr_actions_responses .= "
            <tr>
                <td>" . $count++ . "</td>
                <td>" . $question_type . "</td>
                <td>" . $action_question . "</td>
                <td>" . $answer . "</td>
            </tr>
        ";
    }
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
                            <th scope="col">#</th>
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