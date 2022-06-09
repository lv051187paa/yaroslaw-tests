<div class="pr-3">
    <h1>Import/Export тестів</h1>
    <div class="accordion w100" id="generation-tips-accordion">
        <div class="w100">
            <div class="card-header p-0" id="generation-tips">
                <h2 class="mb-0">
                    <button class="btn btn-info btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-generation-tips" aria-expanded="true" aria-controls="collapse-generation-tips">
                        Підказки
                    </button>
                </h2>
            </div>

            <div id="collapse-generation-tips" class="collapse" aria-labelledby="generation-tips" data-parent="#generation-tips-accordion">
                <div class="card-body">
                    <p>Структура JSON для створення новго тесту</p>
                    <p>"test_description" і "question_description" можна не задавати взагалі, якщо нема потреби</p>
                    <p><strong>question_type</strong> може бути тільки в двох варіантах <strong>single</strong> або <strong>multiple</strong></p>
                    <p><strong>В першому прикладі</strong> буде створено тест, і питання, в якому варіанти відповіді будуть відображатись у вигляді оцінок від 0 до 4</p>
                    <p><strong>В другому варіанті</strong> буде створений тест з питанням, в якому вручну задаються варіанти відповіді, тобто скільки варіантів буде задано автором, такі і будуть відображені в тесті</p>
                    <pre>
{
    "test_name": "New test",
    "test_description": "some description text", (optional)
    "question_list": [
            {
                "question_type": "single",
                "question_description": "some description text", (optional)
                "question_text": "Нервозність або внутрішнє тремтіння",
                "rate_min": 0,
                "rate_max": 4,
                "min_rate_description": "Зовсім немає",
                "max_rate_description": "Дуже сильно"
            }
        ]
   }
                    </pre>
                    або
                    <pre>
{
    "test_name": "New test",
    "test_description": "some description text", (optional)
    "question_list": [
        {
            "question_type": "single",
            "question_description": "some description text", (optional)
            "question_text": "Нервозність або внутрішнє тремтіння",
            "question_option_list": [
                {
                    "option_text": "Варіант відповіді на запитання",
                    "option_value": "Якась оцінка варіанту відповіді" (обов'язково в числовому варіанті)
                }
            ]
        }
    ]
}
                     </pre>
                </div>
            </div>
        </div>
    </div>
    <div>
        <form>
            <div class="form-group">
                <label for="test-struture">Структура тесту</label>
                <textarea name="test_struture" class="form-control" id="test-struture" rows="10"></textarea>
                <div class="test-struture-validation-text invalid-feedback">
                </div>
            </div>
            <button id="import-test" class="btn btn-outline-primary" type="button">Зберегти</button>
        </form>
    </div>
</div>
