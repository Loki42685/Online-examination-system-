<?php

function keywordScore($student, $model){
    $model_words = explode(" ", strtolower($model));
    $student_words = explode(" ", strtolower($student));

    $match = 0;
    foreach($model_words as $word){
        if(in_array($word, $student_words)){
            $match++;
        }
    }

    if(count($model_words) == 0) return 0;

    return $match / count($model_words);
}

function lengthScore($student, $model){
    $s_len = str_word_count($student);
    $m_len = str_word_count($model);

    if($m_len == 0) return 0;

    return min($s_len / $m_len, 1);
}

function evaluateAnswer($student, $model, $max_marks){
    $k = keywordScore($student, $model);
    $l = lengthScore($student, $model);

    $final = ($k * 0.5) + ($l * 0.5);

    return round($final * $max_marks);
}


function predictScore($previous, $current){
    return round((0.6 * $previous) + (0.4 * $current));
}


function riskLevel($predicted){
    if($predicted < 40){
        return "🔴 High Risk";
    } else if($predicted < 60){
        return "🟡 Medium Risk";
    } else {
        return "🟢 Safe";
    }
}
?>