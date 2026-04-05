<?php
session_start();
include("config.php");

if(!isset($_SESSION['teacher_id'])){
    header("Location: teacher-login.php");
    exit();
}

$teacher_id = $_SESSION['teacher_id'];
$exam_id = isset($_GET['exam_id']) ? $_GET['exam_id'] : 0;

// Fetch all exams assigned to this teacher
$exams = mysqli_query($conn, "SELECT id, exam_name, exam_type FROM exams WHERE teacher_id='$teacher_id' AND status='active'");

// If no exam is selected yet, show dropdown
if($exam_id == 0){
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Select Exam</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body{font-family:'Segoe UI',sans-serif;background:#f2f9ff;padding:40px;}
            .container{background:white;padding:30px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);max-width:600px;margin:auto;text-align:center;}
            select{width:100%;padding:10px;margin:20px 0;border-radius:6px;border:1px solid #ccc;}
            label{font-weight:bold;}
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Select Exam to Add Questions</h2>
            <label>Exam</label>
           <select onchange="if(this.value!=''){ window.location='<?php echo basename(__FILE__); ?>?exam_id='+this.value; }">
                <option value="">-- Select Exam --</option>
                <?php while($e = mysqli_fetch_assoc($exams)){ ?>
                    <option value="<?php echo $e['id']; ?>">
                        <?php echo $e['exam_name'] . " (" . strtoupper($e['exam_type']) . ")"; ?>
                    </option>
                <?php } ?>
            </select>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Fetch selected exam details
$exam_query = "SELECT exam_type, exam_name FROM exams WHERE id='$exam_id' AND teacher_id='$teacher_id'";
$exam_result = mysqli_query($conn, $exam_query);

if(mysqli_num_rows($exam_result) == 0){
    die("Invalid Exam or you don't have permission.");
}

$exam_row = mysqli_fetch_assoc($exam_result);
$exam_type = $exam_row['exam_type'];
$exam_name = $exam_row['exam_name'];

// Handle form submission
if(isset($_POST['submit'])){
	$correct_answer = isset($_POST['correct_answer']) ? trim($_POST['correct_answer']) : NULL;
    $question_text = trim($_POST['question_text']);
    $marks = $_POST['marks'];

    // Optional fields for MCQ
    $option_a = isset($_POST['option_a']) ? trim($_POST['option_a']) : NULL;
    $option_b = isset($_POST['option_b']) ? trim($_POST['option_b']) : NULL;
    $option_c = isset($_POST['option_c']) ? trim($_POST['option_c']) : NULL;
    $option_d = isset($_POST['option_d']) ? trim($_POST['option_d']) : NULL;
    $correct_option = isset($_POST['correct_option']) ? $_POST['correct_option'] : NULL;

    // Enforce MCQ fields if exam type is MCQ
    if($exam_type == 'mcq'){
        if(empty($option_a) || empty($option_b) || empty($option_c) || empty($option_d) || empty($correct_option)){
            echo "<script>alert('All MCQ options and correct option are required');</script>";
            exit();
        }
    }

    // Count existing questions of this type
    $count_query = "SELECT COUNT(*) as total FROM questions WHERE exam_id='$exam_id' AND question_type='$exam_type'";
    $count_result = mysqli_query($conn, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);
    $total_questions = $count_row['total'];

    if($exam_type == 'mcq' && $total_questions >= 20){
        echo "<script>alert('Maximum 20 MCQ questions allowed');</script>";
        exit();
    }

    if($exam_type == 'descriptive' && $total_questions >= 3){
        echo "<script>alert('Maximum 3 descriptive questions allowed');</script>";
        exit();
    }

   $query = "INSERT INTO questions 
(exam_id, question_text, question_type, option_a, option_b, option_c, option_d, correct_option, marks, correct_answer)
VALUES 
('$exam_id','$question_text','$exam_type','$option_a','$option_b','$option_c','$option_d','$correct_option','$marks','$correct_answer')";

    if(mysqli_query($conn,$query)){
        echo "<script>alert('Question Added Successfully');</script>";
        echo "<script>window.location='add-question.php?exam_id=".$exam_id."';</script>";
    }else{
        echo "Error: ".mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Question</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{font-family:'Segoe UI',sans-serif;background:#f2f9ff;padding:40px;}
.container{background:white;padding:30px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);max-width:600px;margin:auto;}
h2{color:#1e3c72;margin-bottom:20px;text-align:center;}
input, textarea, select{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:5px;}
button{background:#1e3c72;color:white;padding:10px;border:none;border-radius:5px;width:100%;font-size:16px;cursor:pointer;}
button:hover{background:#16325c;}
.back{display:block;margin-top:15px;text-align:center;text-decoration:none;color:#1e3c72;}
</style>
</head>
<body>
<div class="container">
<h2>Add Question to "<?php echo $exam_name; ?>" (<?php echo strtoupper($exam_type); ?>)</h2>
<form method="POST">
    <label>Question</label>
    <textarea name="question_text" required></textarea>

    <div id="mcq_fields">
        <label>Option A</label>
        <input type="text" name="option_a">
        <label>Option B</label>
        <input type="text" name="option_b">
        <label>Option C</label>
        <input type="text" name="option_c">
        <label>Option D</label>
        <input type="text" name="option_d">
        <label>Correct Option</label>
        <select name="correct_option">
            <option value="A">Option A</option>
            <option value="B">Option B</option>
            <option value="C">Option C</option>
            <option value="D">Option D</option>
        </select>
    </div>
	<div id="descriptive_box" style="display:none;">
    <label>Model Answer (for AI Evaluation)</label>
    <textarea name="correct_answer" rows="4"></textarea>
</div>
    <label>Marks</label>
    <input type="number" name="marks" required>

    <button type="submit" name="submit">Add Question</button>
</form>
<a class="back" href="view-exams.php">Back</a>
</div>

<script>
window.onload = function(){
    var type = "<?php echo $exam_type; ?>";

    var mcq = document.getElementById("mcq_fields");
    var desc = document.getElementById("descriptive_box");

    if(type === "mcq"){
        mcq.style.display = "block";
        desc.style.display = "none";
    } else {
        mcq.style.display = "none";
        desc.style.display = "block";
    }
};
</script>
</body>
</html>