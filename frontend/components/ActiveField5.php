<?php

// frontend/components/ActiveField.php

namespace frontend\components;

use yii\bootstrap5\ActiveField;

class ActiveField5 extends ActiveField
{
    public function passwordInput($options = []): ActiveField5
    {
        $this->template = '
            <div class="mb-3" data-password="bar">
                {label}
                <div class="input-group">{input}</div>
                <div class="password-bar my-2"></div>
                <p class="text-muted fs-xs mb-0">Use 8+ characters with letters, numbers & symbols.</p>
                {error}
            </div>
        ';

        return parent::passwordInput($options);
    }
}
