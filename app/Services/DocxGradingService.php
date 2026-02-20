<?php

namespace App\Services;

use PhpOffice\PhpWord\IOFactory;

class DocxGradingService
{
    /**
     * Extract text content from a .docx file.
     *
     * @param string $filePath Absolute path to the .docx file
     * @return string Extracted text content
     */
    public function extractText(string $filePath): string
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $phpWord = IOFactory::load($filePath);
        $text = '';

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                $text .= $this->extractTextFromElement($element) . "\n";
            }
        }

        return trim($text);
    }

    /**
     * Recursively extract text from document elements.
     */
    private function extractTextFromElement($element): string
    {
        $text = '';

        // TextRun contains multiple Text elements
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $text .= $this->extractTextFromElement($child);
            }
        }

        // Text element
        if (method_exists($element, 'getText')) {
            $text .= $element->getText();
        }

        return $text;
    }

    /**
     * Compare submitted text with master text and calculate accuracy.
     * ULTRA STRICT MODE: Counts whitespace and line breaks as characters.
     *
     * @param string $submittedText Text from student's submission
     * @param string $masterText Correct/expected text
     * @return array Contains accuracy percentage and detailed results
     */
    public function compareTexts(string $submittedText, string $masterText): array
    {
        // Normalize texts with enhanced normalization for Thai
        $submittedNormalized = $this->normalizeText($submittedText);
        $masterNormalized = $this->normalizeText($masterText);

        // ULTRA STRICT: Analyze whitespace and line breaks SEPARATELY
        $whitespaceAnalysis = $this->analyzeWhitespace($submittedNormalized, $masterNormalized);

        // CHARACTER-LEVEL COMPARISON for precise accuracy
        // Convert strings to character arrays (UTF-8 safe)
        $submittedChars = preg_split('//u', $submittedNormalized, -1, PREG_SPLIT_NO_EMPTY);
        $masterChars = preg_split('//u', $masterNormalized, -1, PREG_SPLIT_NO_EMPTY);

        $totalMasterChars = count($masterChars);
        $totalSubmittedChars = count($submittedChars);

        if ($totalMasterChars === 0) {
            return [
                'accuracy' => 0,
                'correct_chars' => 0,
                'total_chars' => 0,
                'wrong_chars' => 0,
                'missing_chars' => 0,
                'extra_chars' => $totalSubmittedChars,
                'correct_words' => 0,
                'total_words' => 0,
                'wrong_words' => 0,
                'missing_words' => 0,
                'extra_words' => 0,
                'whitespace_analysis' => $whitespaceAnalysis,
                'details' => [],
            ];
        }

        // Use LCS for character-level comparison
        $lcsLength = $this->computeCharacterLCSLength($masterChars, $submittedChars);
        $correctChars = $lcsLength;

        // Calculate character-level metrics
        $wrongChars = $totalMasterChars - $correctChars;
        $missingChars = max(0, $totalMasterChars - $totalSubmittedChars);
        $extraChars = max(0, $totalSubmittedChars - $totalMasterChars);

        // Character-based accuracy
        $accuracy = ($correctChars / $totalMasterChars) * 100;

        // Also compute word-level stats for reference
        $submittedWords = preg_split('/\s+/u', $submittedNormalized, -1, PREG_SPLIT_NO_EMPTY);
        $masterWords = preg_split('/\s+/u', $masterNormalized, -1, PREG_SPLIT_NO_EMPTY);
        $totalMasterWords = count($masterWords);
        $totalSubmittedWords = count($submittedWords);

        // Count exact word matches
        $correctWords = 0;
        $minWords = min($totalMasterWords, $totalSubmittedWords);
        for ($i = 0; $i < $minWords; $i++) {
            if ($masterWords[$i] === $submittedWords[$i]) {
                $correctWords++;
            }
        }

        return [
            'accuracy' => round($accuracy, 2),
            'correct_chars' => $correctChars,
            'total_chars' => $totalMasterChars,
            'wrong_chars' => $wrongChars,
            'missing_chars' => $missingChars,
            'extra_chars' => $extraChars,
            'correct_words' => $correctWords,
            'total_words' => $totalMasterWords,
            'wrong_words' => $totalMasterWords - $correctWords,
            'missing_words' => max(0, $totalMasterWords - $totalSubmittedWords),
            'extra_words' => max(0, $totalSubmittedWords - $totalMasterWords),
            'whitespace_analysis' => $whitespaceAnalysis,
            'details' => [],
        ];
    }

    /**
     * Analyze whitespace differences between two texts.
     * ULTRA STRICT: Counts spaces, tabs, and line breaks separately.
     *
     * @param string $submitted Normalized submitted text
     * @param string $master Normalized master text
     * @return array Detailed whitespace analysis
     */
    private function analyzeWhitespace(string $submitted, string $master): array
    {
        // Count spaces in both texts
        $masterSpaces = preg_match_all('/ /', $master);
        $submittedSpaces = preg_match_all('/ /', $submitted);

        // Count tabs in both texts
        $masterTabs = preg_match_all('/\t/', $master);
        $submittedTabs = preg_match_all('/\t/', $submitted);

        // Count line breaks in both texts
        $masterLineBreaks = preg_match_all('/\n/', $master);
        $submittedLineBreaks = preg_match_all('/\n/', $submitted);

        // Count consecutive spaces (potential double-space errors)
        $masterDoubleSpaces = preg_match_all('/  +/', $master);
        $submittedDoubleSpaces = preg_match_all('/  +/', $submitted);

        // Count leading/trailing spaces in lines
        $masterLines = explode("\n", $master);
        $submittedLines = explode("\n", $submitted);

        $masterLeadingSpaces = 0;
        $masterTrailingSpaces = 0;
        foreach ($masterLines as $line) {
            if (preg_match('/^( +)/', $line, $m)) {
                $masterLeadingSpaces += strlen($m[1]);
            }
            if (preg_match('/( +)$/', $line, $m)) {
                $masterTrailingSpaces += strlen($m[1]);
            }
        }

        $submittedLeadingSpaces = 0;
        $submittedTrailingSpaces = 0;
        foreach ($submittedLines as $line) {
            if (preg_match('/^( +)/', $line, $m)) {
                $submittedLeadingSpaces += strlen($m[1]);
            }
            if (preg_match('/( +)$/', $line, $m)) {
                $submittedTrailingSpaces += strlen($m[1]);
            }
        }

        // Calculate differences
        $spaceDiff = abs($masterSpaces - $submittedSpaces);
        $tabDiff = abs($masterTabs - $submittedTabs);
        $lineBreakDiff = abs($masterLineBreaks - $submittedLineBreaks);
        $doubleSpaceDiff = abs($masterDoubleSpaces - $submittedDoubleSpaces);
        $leadingSpaceDiff = abs($masterLeadingSpaces - $submittedLeadingSpaces);
        $trailingSpaceDiff = abs($masterTrailingSpaces - $submittedTrailingSpaces);

        $totalErrors = $spaceDiff + $tabDiff + $lineBreakDiff;
        $passed = ($totalErrors === 0);

        return [
            'passed' => $passed,
            'total_errors' => $totalErrors,
            'master_spaces' => $masterSpaces,
            'submitted_spaces' => $submittedSpaces,
            'space_diff' => $spaceDiff,
            'master_tabs' => $masterTabs,
            'submitted_tabs' => $submittedTabs,
            'tab_diff' => $tabDiff,
            'master_line_breaks' => $masterLineBreaks,
            'submitted_line_breaks' => $submittedLineBreaks,
            'line_break_diff' => $lineBreakDiff,
            'master_double_spaces' => $masterDoubleSpaces,
            'submitted_double_spaces' => $submittedDoubleSpaces,
            'double_space_diff' => $doubleSpaceDiff,
            'master_leading_spaces' => $masterLeadingSpaces,
            'submitted_leading_spaces' => $submittedLeadingSpaces,
            'leading_space_diff' => $leadingSpaceDiff,
            'master_trailing_spaces' => $masterTrailingSpaces,
            'submitted_trailing_spaces' => $submittedTrailingSpaces,
            'trailing_space_diff' => $trailingSpaceDiff,
            'master_lines' => count($masterLines),
            'submitted_lines' => count($submittedLines),
        ];
    }

    /**
     * Compute LCS length for character arrays using optimized space algorithm.
     * Returns only the length (not the actual sequence) for memory efficiency.
     *
     * @param array $master Master character array
     * @param array $submitted Submitted character array
     * @return int Length of LCS
     */
    private function computeCharacterLCSLength(array $master, array $submitted): int
    {
        $m = count($master);
        $n = count($submitted);

        // Use space-optimized DP (only keep 2 rows)
        $prev = array_fill(0, $n + 1, 0);
        $curr = array_fill(0, $n + 1, 0);

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($master[$i - 1] === $submitted[$j - 1]) {
                    $curr[$j] = $prev[$j - 1] + 1;
                } else {
                    $curr[$j] = max($prev[$j], $curr[$j - 1]);
                }
            }
            // Swap rows
            $temp = $prev;
            $prev = $curr;
            $curr = $temp;
            // Reset curr for next iteration
            $curr = array_fill(0, $n + 1, 0);
        }

        return $prev[$n];
    }

    /**
     * Compute Longest Common Subsequence between two word arrays.
     * Uses dynamic programming for efficiency.
     *
     * @param array $master Master word array
     * @param array $submitted Submitted word array
     * @return array The LCS as array of matched words with their positions
     */
    private function computeLCS(array $master, array $submitted): array
    {
        $m = count($master);
        $n = count($submitted);

        // Build LCS length table
        $dp = array_fill(0, $m + 1, array_fill(0, $n + 1, 0));

        for ($i = 1; $i <= $m; $i++) {
            for ($j = 1; $j <= $n; $j++) {
                if ($this->wordsMatch($master[$i - 1], $submitted[$j - 1])) {
                    $dp[$i][$j] = $dp[$i - 1][$j - 1] + 1;
                } else {
                    $dp[$i][$j] = max($dp[$i - 1][$j], $dp[$i][$j - 1]);
                }
            }
        }

        // Backtrack to find the actual LCS
        $lcs = [];
        $i = $m;
        $j = $n;

        while ($i > 0 && $j > 0) {
            if ($this->wordsMatch($master[$i - 1], $submitted[$j - 1])) {
                array_unshift($lcs, [
                    'word' => $master[$i - 1],
                    'master_pos' => $i - 1,
                    'submitted_pos' => $j - 1,
                ]);
                $i--;
                $j--;
            } elseif ($dp[$i - 1][$j] > $dp[$i][$j - 1]) {
                $i--;
            } else {
                $j--;
            }
        }

        return $lcs;
    }

    /**
     * Check if two words match.
     * STRICT MODE: Words must match exactly after normalization.
     * No tolerance for typos or similar characters.
     *
     * @param string $word1
     * @param string $word2
     * @return bool
     */
    private function wordsMatch(string $word1, string $word2): bool
    {
        // Exact match
        if ($word1 === $word2) {
            return true;
        }

        // Normalize both words and compare
        $normalized1 = $this->normalizeWord($word1);
        $normalized2 = $this->normalizeWord($word2);

        if ($normalized1 === $normalized2) {
            return true;
        }

        // STRICT MODE: No similarity tolerance
        // Previously allowed 95% similarity for words > 3 characters
        // Now words must match exactly - no tolerance for typos
        // (Commented out for reference:)
        // if (mb_strlen($normalized1) > 3 && mb_strlen($normalized2) > 3) {
        //     $similarity = 0;
        //     similar_text($normalized1, $normalized2, $similarity);
        //     if ($similarity >= 95) {
        //         return true;
        //     }
        // }

        return false;
    }

    /**
     * Normalize a single word for comparison.
     * STRICT MODE: Only removes invisible characters and normalizes Unicode.
     * Case and punctuation are preserved for exact matching.
     *
     * @param string $word
     * @return string
     */
    private function normalizeWord(string $word): string
    {
        // STRICT MODE: Do NOT convert to lowercase
        // Case must match exactly
        // (Previously: $word = mb_strtolower($word, 'UTF-8');)

        // Remove zero-width characters and other invisible Unicode characters
        // These are invisible and users cannot control them
        $word = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $word);

        // Normalize Unicode (NFC normalization) - essential for Thai
        if (class_exists('Normalizer')) {
            $word = \Normalizer::normalize($word, \Normalizer::NFC);
        }

        // STRICT MODE: Do NOT remove leading/trailing punctuation
        // Punctuation must match exactly
        // (Previously: $word = preg_replace('/^[\p{P}\p{S}]+|[\p{P}\p{S}]+$/u', '', $word);)

        return trim($word);
    }

    /**
     * Build detailed comparison using LCS alignment.
     *
     * @param array $masterWords
     * @param array $submittedWords
     * @param array $lcs
     * @return array
     */
    private function buildComparisonDetails(array $masterWords, array $submittedWords, array $lcs): array
    {
        $details = [];
        $lcsIndex = 0;
        $submittedIndex = 0;

        for ($masterIndex = 0; $masterIndex < count($masterWords); $masterIndex++) {
            $masterWord = $masterWords[$masterIndex];

            // Check if this master word is in the LCS
            if ($lcsIndex < count($lcs) && $lcs[$lcsIndex]['master_pos'] === $masterIndex) {
                // Found in LCS - it's a correct match
                $details[] = [
                    'position' => $masterIndex + 1,
                    'expected' => $masterWord,
                    'submitted' => $submittedWords[$lcs[$lcsIndex]['submitted_pos']],
                    'status' => 'correct',
                ];
                $submittedIndex = $lcs[$lcsIndex]['submitted_pos'] + 1;
                $lcsIndex++;
            } else {
                // Not in LCS - check if it's wrong or missing
                // Look ahead in submitted words for a potential match
                $foundWrong = false;
                if ($submittedIndex < count($submittedWords)) {
                    // Check if there's a submitted word at this position that doesn't match
                    $details[] = [
                        'position' => $masterIndex + 1,
                        'expected' => $masterWord,
                        'submitted' => $submittedWords[$submittedIndex] ?? null,
                        'status' => 'wrong',
                    ];
                    $submittedIndex++;
                    $foundWrong = true;
                }

                if (!$foundWrong) {
                    $details[] = [
                        'position' => $masterIndex + 1,
                        'expected' => $masterWord,
                        'submitted' => null,
                        'status' => 'missing',
                    ];
                }
            }
        }

        // Add any extra words from submission
        while ($submittedIndex < count($submittedWords)) {
            // Check if this word is already accounted for in LCS
            $isInLcs = false;
            foreach ($lcs as $match) {
                if ($match['submitted_pos'] === $submittedIndex) {
                    $isInLcs = true;
                    break;
                }
            }

            if (!$isInLcs) {
                $details[] = [
                    'position' => count($masterWords) + 1,
                    'expected' => null,
                    'submitted' => $submittedWords[$submittedIndex],
                    'status' => 'extra',
                ];
            }
            $submittedIndex++;
        }

        return $details;
    }

    /**
     * Calculate score based on accuracy and max score.
     *
     * @param float $accuracy Accuracy percentage (0-100)
     * @param int $maxScore Maximum score for the assignment
     * @return float Calculated score
     */
    public function calculateScore(float $accuracy, int $maxScore): float
    {
        return round(($accuracy / 100) * $maxScore, 1);
    }

    /**
     * Normalize text for comparison.
     * ULTRA STRICT MODE: Preserves ALL whitespace and line breaks exactly.
     * Only normalizes Unicode characters that users cannot control.
     * 
     * Whitespace that IS preserved:
     * - Multiple spaces (students must type exact number)
     * - Tabs (converted to spaces for visibility, but counted)
     * - Line breaks (exact number must match)
     * - Leading/trailing whitespace in each line
     * 
     * Characters that ARE normalized (invisible to users):
     * - Zero-width characters (U+200B-U+200D, U+FEFF)
     * - Unicode normalization (NFC) for Thai characters
     * - Different line break styles (CR, LF, CRLF → LF)
     */
    private function normalizeText(string $text): string
    {
        // Normalize Unicode (NFC normalization for consistent Thai character representation)
        // This is essential for Thai language to compare correctly
        if (class_exists('Normalizer')) {
            $text = \Normalizer::normalize($text, \Normalizer::NFC);
        }

        // Remove ONLY zero-width characters and other invisible Unicode characters
        // These are characters users cannot see or control at all
        $text = preg_replace('/[\x{200B}-\x{200D}\x{FEFF}]/u', '', $text);

        // Convert non-breaking space to regular space (users can't distinguish these visually)
        $text = preg_replace('/\x{00A0}/u', ' ', $text);

        // Normalize line breaks to \n (different OS use different line breaks)
        // This is the ONLY whitespace normalization we do
        $text = preg_replace('/\r\n|\r/', "\n", $text);

        // ULTRA STRICT MODE: Do NOT trim!
        // Leading/trailing whitespace must match exactly
        // (Previously: $text = trim($text);)

        // ULTRA STRICT MODE: Do NOT collapse multiple spaces!
        // Students must type exact number of spaces as the original

        // ULTRA STRICT MODE: Do NOT collapse multiple line breaks!
        // Students must match exact line breaks as the original

        return $text;
    }

    /**
     * Grade a submission automatically.
     *
     * @param string $filePath Path to the submitted .docx file
     * @param string $masterText The correct text to compare against
     * @param int $maxScore Maximum score for the assignment
     * @param bool $checkFormatting Whether to check formatting
     * @param string|null $masterFilePath Path to master file for formatting comparison
     * @return array Grading results
     */
    public function gradeSubmission(string $filePath, string $masterText, int $maxScore, bool $checkFormatting = false, ?string $masterFilePath = null): array
    {
        $extractionDone = false;
        
        if ($masterFilePath && file_exists($masterFilePath)) {
            // 1. Try Microservice API first
            $microserviceUrl = config('services.docx_grader.url', 'http://127.0.0.1:8000/grade');
            $useMicroservice = env('USE_PYTHON_MICROSERVICE', true);

            if ($useMicroservice) {
                try {
                    $response = \Illuminate\Support\Facades\Http::timeout(30)
                        ->attach('submitted_file', file_get_contents($filePath), 'submitted.docx')
                        ->attach('master_file', file_get_contents($masterFilePath), 'master.docx')
                        ->post($microserviceUrl);

                    if ($response->successful()) {
                        $json = $response->json();
                        if ($json && !isset($json['error']) && isset($json['accuracy'])) {
                            $comparison = $json;
                            $submittedText = $comparison['submitted_text'] ?? $this->extractText($filePath);
                            $extractionDone = true;
                        }
                    }
                } catch (\Exception $e) {
                    // Microservice failed or unreachable, fallback silently
                }
            }
            
            // 2. Fallback to local shell_exec if API failed
            if (!$extractionDone) {
                $pythonScript = base_path('scripts/validate_docx.py');
                $isShellExecAvailable = function_exists('shell_exec') && 
                                        !in_array('shell_exec', array_map('trim', explode(',', ini_get('disable_functions'))));

                if (file_exists($pythonScript) && $isShellExecAvailable) {
                    $escapedScript = escapeshellarg($pythonScript);
                    $escapedFile = escapeshellarg($filePath);
                    $escapedMaster = escapeshellarg($masterFilePath);
                    
                    $command = "python {$escapedScript} {$escapedFile} {$escapedMaster} 2>&1";
                    $output = @shell_exec($command);
                    
                    $json = json_decode($output, true);
                    if ($json && !isset($json['error']) && isset($json['accuracy'])) {
                        $comparison = $json;
                        $submittedText = $comparison['submitted_text'] ?? $this->extractText($filePath);
                        $extractionDone = true;
                    }
                }
            }
        }

        // 3. Final Fallback to pure PHP if all Python methods failed or master absent
        if (!$extractionDone) {
            $submittedText = $this->extractText($filePath);
            $comparison = $this->compareTexts($submittedText, $masterText);
            $comparison['submitted_text'] = $submittedText;
        }

        // Text accuracy score (if formatting check enabled, this is 70% weight)
        $textAccuracy = $comparison['accuracy'];

        // Check numeral format consistency
        $numeralCheck = $this->compareNumeralFormats($submittedText, $masterText);

        $result = [
            'score' => 0,
            'accuracy' => $textAccuracy,
            'correct_words' => $comparison['correct_words'],
            'total_words' => $comparison['total_words'],
            'wrong_words' => $comparison['wrong_words'],
            'missing_words' => $comparison['missing_words'],
            'extra_words' => $comparison['extra_words'],
            'submitted_text' => $submittedText,
            'details' => $comparison['details'],
            'numeral_check' => $numeralCheck,
        ];

        if ($checkFormatting) {
            // If master file provided, extract formatting rules from it
            $rules = null;
            if ($masterFilePath && file_exists($masterFilePath)) {
                $rules = $this->extractFormattingRulesFromFile($masterFilePath);
            }

            $formattingResult = $this->checkFormatting($filePath, $rules);

            // Determine expected numeral type from rules (master file) or from comparison
            $expectedNumeralType = $rules['numeral_type'] ?? null;
            $expectedNumeralLabel = match ($expectedNumeralType) {
                'thai' => 'เลขไทย',
                'arabic' => 'เลขอารบิก',
                'mixed' => 'เลขไทยและเลขอารบิก',
                'none' => 'ไม่มีตัวเลข',
                default => $numeralCheck['master_type'],
            };

            // Add numeral check to formatting checks
            $formattingResult['checks'][] = [
                'element' => 'numeral_format',
                'label' => 'รูปแบบตัวเลข (ไทย/อารบิก)',
                'expected' => 'ตรงกับต้นฉบับ (' . $expectedNumeralLabel . ')',
                'actual' => $numeralCheck['submitted_type'],
                'passed' => $numeralCheck['passed'],
            ];

            // Update formatting score with numeral check
            if ($numeralCheck['passed']) {
                $formattingResult['passed']++;
            }
            $formattingResult['total']++;
            $formattingResult['score'] = $formattingResult['total'] > 0
                ? round(($formattingResult['passed'] / $formattingResult['total']) * 100, 2)
                : 0;

            $result['formatting'] = $formattingResult;
            $result['formatting_score'] = $formattingResult['score'];

            // Combined score: 70% text + 30% formatting
            $combinedAccuracy = ($textAccuracy * 0.7) + ($formattingResult['score'] * 0.3);
            $result['combined_accuracy'] = round($combinedAccuracy, 2);
            $result['score'] = $this->calculateScore($combinedAccuracy, $maxScore);
        } else {
            $result['score'] = $this->calculateScore($textAccuracy, $maxScore);
        }

        return $result;
    }

    /**
     * Extract formatting rules from a master file.
     * This extracts ALL formatting details from the master file to use as grading criteria.
     */
    public function extractFormattingRulesFromFile(string $filePath): array
    {
        $extracted = $this->extractFormatting($filePath);
        $masterText = $this->extractText($filePath);

        // Detect header labels from master file (bold texts that match common headers)
        $detectedHeaders = [];
        $headerFontSize = 16; // default
        $commonHeaders = ['ส่วนราชการ', 'ที่', 'วันที่', 'เรื่อง', 'เรียน', 'อ้างถึง', 'สิ่งที่ส่งมาด้วย'];
        $boldTexts = $extracted['bold_texts'] ?? [];

        foreach ($boldTexts as $boldItem) {
            foreach ($commonHeaders as $header) {
                if (strpos($boldItem['text'], $header) !== false) {
                    if (!in_array($header, $detectedHeaders)) {
                        $detectedHeaders[] = $header;
                        // Use the first found header's size as reference
                        if ($boldItem['size'] && count($detectedHeaders) === 1) {
                            $headerFontSize = $boldItem['size'];
                        }
                    }
                }
            }
        }

        // Detect title formatting (บันทึกข้อความ or similar)
        $titleFontSize = 29; // default
        $titleFound = false;
        foreach ($boldTexts as $boldItem) {
            if (strpos($boldItem['text'], 'บันทึกข้อความ') !== false) {
                $titleFound = true;
                if ($boldItem['size']) {
                    $titleFontSize = $boldItem['size'];
                }
                break;
            }
        }

        // Detect numeral type from master text
        $hasThaiNumerals = $this->containsThaiNumerals($masterText);
        $hasArabicNumerals = $this->containsArabicNumerals($masterText);
        $numeralType = 'none';
        if ($hasThaiNumerals && $hasArabicNumerals) {
            $numeralType = 'mixed';
        } elseif ($hasThaiNumerals) {
            $numeralType = 'thai';
        } elseif ($hasArabicNumerals) {
            $numeralType = 'arabic';
        }

        // Convert first line indent from twips to cm
        $indentTwips = $extracted['primary_indent'] ?? 0;
        $indentCm = $indentTwips > 0 ? round($indentTwips / 567, 1) : 2.5;

        // Determine line spacing
        $lineSpacing = $extracted['primary_line_spacing'] ?? 1.0;

        // Build comprehensive rules from extracted formatting
        return [
            // Font settings (from master file)
            'font_name' => $extracted['primary_font'] ?? 'TH SarabunPSK',
            'font_size' => $extracted['primary_font_size'] ?? 16,

            // Page margins (from master file)
            'margin_top' => isset($extracted['margins']['top']) ? round($extracted['margins']['top'], 1) : 2.5,
            'margin_left' => isset($extracted['margins']['left']) ? round($extracted['margins']['left'], 1) : 3.0,
            'margin_bottom' => isset($extracted['margins']['bottom']) ? round($extracted['margins']['bottom'], 1) : 2.0,
            'margin_right' => isset($extracted['margins']['right']) ? round($extracted['margins']['right'], 1) : 2.0,

            // Paper settings (from master file)
            'paper_size' => $extracted['paper_size'] ?? 'A4',
            'orientation' => $extracted['orientation'] ?? 'portrait',

            // Paragraph settings (from master file)
            'alignment' => $extracted['primary_alignment'] ?? 'both',
            'first_line_indent' => $indentCm, // in cm
            'line_spacing' => is_numeric($lineSpacing) ? $lineSpacing : 1.0,

            // Title settings (from master file)
            'title_font_size' => $titleFontSize,
            'title_bold' => true,

            // Header labels (detected from master file)
            'header_labels' => !empty($detectedHeaders) ? $detectedHeaders : ['ส่วนราชการ', 'ที่', 'วันที่', 'เรื่อง'],
            'header_font_size' => $headerFontSize,
            'header_bold' => true,

            // Numeral type (from master file)
            'numeral_type' => $numeralType,

            // Store all fonts found for reference
            'all_fonts' => $extracted['fonts'] ?? [],
            'all_font_sizes' => $extracted['font_sizes'] ?? [],
        ];
    }

    /**
     * Default formatting rules for Thai government documents (บันทึกข้อความ).
     * Based on official Thai government document standards.
     */
    public function getDefaultFormattingRules(): array
    {
        // 1 inch = 1440 twips, 1 cm = 567 twips (approx)
        return [
            // Font settings
            'font_name' => 'TH SarabunPSK',
            'font_size' => 16, // points for body text

            // Title settings (บันทึกข้อความ)
            'title_font_size' => 29, // pt
            'title_bold' => true,
            'title_spacing_before' => 6, // points
            'title_line_spacing' => 35, // exact line spacing in points

            // Page margins (in cm, converted to twips for comparison)
            'margin_top' => 2.5,    // cm
            'margin_left' => 3.0,   // cm
            'margin_bottom' => 2.0, // cm
            'margin_right' => 2.0,  // cm

            // Paper size
            'paper_size' => 'A4',
            'orientation' => 'portrait',

            // Paragraph settings
            'first_line_indent' => 2.5, // cm (previously in twips, now in cm for consistency)
            'line_spacing' => 1.0, // single line spacing
            'alignment' => 'both', // justify

            // Header labels (ส่วนราชการ, ที่, วันที่, เรื่อง) - must be bold, 20pt
            'header_labels' => ['ส่วนราชการ', 'ที่', 'วันที่', 'เรื่อง'],
            'header_font_size' => 20, // pt
            'header_bold' => true,
        ];
    }

    /**
     * Extract fonts directly from .docx XML content.
     * This is more reliable than PhpWord API for some documents.
     */
    public function extractFontsFromXml(string $filePath): array
    {
        $fonts = [];

        // .docx files are ZIP archives
        $zip = new \ZipArchive();
        if ($zip->open($filePath) !== true) {
            return $fonts;
        }

        // Read document.xml for font information
        $documentXml = $zip->getFromName('word/document.xml');
        if ($documentXml) {
            // Extract font names using regex
            // Look for w:rFonts elements with various font attributes
            preg_match_all('/w:rFonts[^>]*w:(ascii|cs|eastAsia|hAnsi)="([^"]+)"/', $documentXml, $matches);
            if (!empty($matches[2])) {
                $fonts = array_merge($fonts, $matches[2]);
            }

            // Also look for font definitions in run properties
            preg_match_all('/<w:rFonts[^\/]*\/>/', $documentXml, $fontElements);
            foreach ($fontElements[0] as $fontElement) {
                // Extract all font attributes
                preg_match_all('/w:[a-zA-Z]+="([^"]+)"/', $fontElement, $attrMatches);
                if (!empty($attrMatches[1])) {
                    $fonts = array_merge($fonts, $attrMatches[1]);
                }
            }
        }

        // Read styles.xml for default font information
        $stylesXml = $zip->getFromName('word/styles.xml');
        if ($stylesXml) {
            preg_match_all('/w:rFonts[^>]*w:(ascii|cs|eastAsia|hAnsi)="([^"]+)"/', $stylesXml, $matches);
            if (!empty($matches[2])) {
                $fonts = array_merge($fonts, $matches[2]);
            }
        }

        // Read fontTable.xml for all defined fonts
        $fontTableXml = $zip->getFromName('word/fontTable.xml');
        if ($fontTableXml) {
            preg_match_all('/w:name\s+w:val="([^"]+)"/', $fontTableXml, $matches);
            if (!empty($matches[1])) {
                $fonts = array_merge($fonts, $matches[1]);
            }
        }

        $zip->close();

        // Filter out empty values and deduplicate
        $fonts = array_filter(array_unique($fonts));

        return array_values($fonts);
    }

    /**
     * Extract formatting information from a .docx file.
     */
    public function extractFormatting(string $filePath): array
    {
        if (!file_exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $phpWord = IOFactory::load($filePath);
        $fonts = [];
        $fontSizes = [];
        $indents = [];
        $lineSpacings = [];
        $alignments = [];
        $boldTexts = [];

        // Page/Section settings
        $margins = [];
        $paperSize = null;
        $orientation = null;

        // Try to get default font from document styles
        if (method_exists($phpWord, 'getDefaultFontName')) {
            $defaultFont = $phpWord->getDefaultFontName();
            if ($defaultFont) {
                $fonts[] = $defaultFont;
            }
        }

        if (method_exists($phpWord, 'getDefaultFontSize')) {
            $defaultSize = $phpWord->getDefaultFontSize();
            if ($defaultSize) {
                $fontSizes[] = $defaultSize;
            }
        }

        // Try to get fonts from document settings
        if (method_exists($phpWord, 'getSettings')) {
            $settings = $phpWord->getSettings();
            if ($settings) {
                // Try to get theme fonts
                if (method_exists($settings, 'getThemeFontLang')) {
                    $themeFontLang = $settings->getThemeFontLang();
                    if ($themeFontLang) {
                        // Log for debugging if needed
                    }
                }
            }
        }

        // Also try extracting fonts directly from XML (most reliable method)
        $xmlFonts = $this->extractFontsFromXml($filePath);
        $fonts = array_merge($fonts, $xmlFonts);

        foreach ($phpWord->getSections() as $section) {
            // Extract section/page settings
            $sectionStyle = $section->getStyle();
            if ($sectionStyle) {
                // Margins (in twips, convert to cm: 1 cm = 567 twips)
                $margins = [
                    'top' => $sectionStyle->getMarginTop() / 567,
                    'left' => $sectionStyle->getMarginLeft() / 567,
                    'bottom' => $sectionStyle->getMarginBottom() / 567,
                    'right' => $sectionStyle->getMarginRight() / 567,
                ];

                // Paper size
                $pageWidth = $sectionStyle->getPageSizeW();
                $pageHeight = $sectionStyle->getPageSizeH();
                // A4 = 11906 x 16838 twips (approx 210 x 297 mm)
                if ($pageWidth && $pageHeight) {
                    if (abs($pageWidth - 11906) < 100 && abs($pageHeight - 16838) < 100) {
                        $paperSize = 'A4';
                        $orientation = 'portrait';
                    } elseif (abs($pageHeight - 11906) < 100 && abs($pageWidth - 16838) < 100) {
                        $paperSize = 'A4';
                        $orientation = 'landscape';
                    }
                }
            }

            foreach ($section->getElements() as $element) {
                $this->extractFormattingFromElement($element, $fonts, $fontSizes, $indents, $lineSpacings, $alignments, $boldTexts);
            }
        }

        return [
            'fonts' => array_unique($fonts),
            'font_sizes' => array_unique($fontSizes),
            'indents' => $indents,
            'line_spacings' => array_unique($lineSpacings),
            'alignments' => array_unique($alignments),
            'bold_texts' => $boldTexts,
            'primary_font' => $this->getMostCommon($fonts),
            'primary_font_size' => $this->getMostCommon($fontSizes),
            'primary_alignment' => $this->getMostCommon($alignments),
            'primary_indent' => $this->getMostCommon($indents), // in twips
            'primary_line_spacing' => $this->getMostCommon($lineSpacings),
            'margins' => $margins,
            'paper_size' => $paperSize,
            'orientation' => $orientation,
        ];
    }

    /**
     * Recursively extract formatting from document elements.
     */
    private function extractFormattingFromElement($element, &$fonts, &$fontSizes, &$indents, &$lineSpacings, &$alignments, &$boldTexts = []): void
    {
        // Check for paragraph properties
        if (method_exists($element, 'getParagraphStyle')) {
            $paragraphStyle = $element->getParagraphStyle();
            if ($paragraphStyle) {
                if (method_exists($paragraphStyle, 'getIndentation')) {
                    $indent = $paragraphStyle->getIndentation();
                    if ($indent && method_exists($indent, 'getFirstLine')) {
                        $firstLine = $indent->getFirstLine();
                        if ($firstLine) {
                            $indents[] = $firstLine;
                        }
                    }
                }
                if (method_exists($paragraphStyle, 'getLineHeight')) {
                    $lineHeight = $paragraphStyle->getLineHeight();
                    if ($lineHeight) {
                        $lineSpacings[] = $lineHeight;
                    }
                }
                if (method_exists($paragraphStyle, 'getAlignment')) {
                    $alignment = $paragraphStyle->getAlignment();
                    if ($alignment) {
                        $alignments[] = $alignment;
                    }
                }
            }
        }

        // Check for font properties in Text elements
        if (method_exists($element, 'getFontStyle')) {
            $fontStyle = $element->getFontStyle();
            if ($fontStyle && is_object($fontStyle)) {
                $fontName = null;

                // Try multiple methods to get font name
                if (method_exists($fontStyle, 'getName')) {
                    $fontName = $fontStyle->getName();
                }

                // Try getHint (for theme fonts)
                if (!$fontName && method_exists($fontStyle, 'getHint')) {
                    $fontName = $fontStyle->getHint();
                }

                // Try getAscii (ASCII font name)
                if (!$fontName && method_exists($fontStyle, 'getAscii')) {
                    $fontName = $fontStyle->getAscii();
                }

                // Try getEastAsia (East Asian font name - often used for Thai)
                if (!$fontName && method_exists($fontStyle, 'getEastAsia')) {
                    $fontName = $fontStyle->getEastAsia();
                }

                // Try getComplexScript (Complex script font - used for Thai, Arabic, etc.)
                if (!$fontName && method_exists($fontStyle, 'getComplexScript')) {
                    $fontName = $fontStyle->getComplexScript();
                }

                if ($fontName) {
                    $fonts[] = $fontName;
                }

                if (method_exists($fontStyle, 'getSize')) {
                    $fontSize = $fontStyle->getSize();
                    if ($fontSize) {
                        $fontSizes[] = $fontSize;
                    }
                }

                // Check for bold text
                if (method_exists($fontStyle, 'isBold') && $fontStyle->isBold()) {
                    if (method_exists($element, 'getText')) {
                        $text = $element->getText();
                        if ($text) {
                            $boldTexts[] = [
                                'text' => $text,
                                'size' => $fontStyle->getSize() ?? null,
                            ];
                        }
                    }
                }
            }
        }

        // Recurse into child elements
        if (method_exists($element, 'getElements')) {
            foreach ($element->getElements() as $child) {
                $this->extractFormattingFromElement($child, $fonts, $fontSizes, $indents, $lineSpacings, $alignments, $boldTexts);
            }
        }
    }

    /**
     * Get the most common value from an array.
     */
    private function getMostCommon(array $values)
    {
        if (empty($values)) {
            return null;
        }
        $counts = array_count_values($values);
        arsort($counts);
        return array_key_first($counts);
    }

    /**
     * Check formatting against expected rules.
     */
    public function checkFormatting(string $filePath, ?array $rules = null): array
    {
        $rules = $rules ?? $this->getDefaultFormattingRules();
        $extracted = $this->extractFormatting($filePath);

        $checks = [];
        $passedCount = 0;
        $totalChecks = 0;

        // STRICT MODE: Check Font Name - must match exactly
        $totalChecks++;
        $expectedFont = $rules['font_name'];
        $actualFont = $extracted['primary_font'];
        // STRICT: Font name must contain the expected font name (case-insensitive)
        $fontPassed = $actualFont && stripos($actualFont, $expectedFont) !== false;
        if ($fontPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'font_name',
            'label' => 'ชื่อฟอนต์',
            'expected' => $expectedFont,
            'actual' => $actualFont ?? 'ไม่พบ',
            'passed' => $fontPassed,
            'deduction' => $fontPassed ? 0 : 1, // Deduct 1 point if wrong
        ];

        // STRICT MODE: Check Font Size - must match EXACTLY (no tolerance)
        $totalChecks++;
        $expectedSize = $rules['font_size'];
        $actualSize = $extracted['primary_font_size'];
        // STRICT: Font size must be EXACTLY the same - NO TOLERANCE
        $sizePassed = $actualSize && $actualSize == $expectedSize;
        if ($sizePassed)
            $passedCount++;
        $checks[] = [
            'element' => 'font_size',
            'label' => 'ขนาดฟอนต์เนื้อหา',
            'expected' => $expectedSize . ' pt (ต้องตรงเป๊ะ)',
            'actual' => $actualSize ? $actualSize . ' pt' : 'ไม่พบ',
            'passed' => $sizePassed,
            'deduction' => $sizePassed ? 0 : 1,
        ];

        // STRICT MODE: Check Page Margins - must match EXACTLY (tolerance only 0.1cm for rounding)
        $margins = $extracted['margins'] ?? [];
        $marginTolerance = 0.1; // Only allow 0.1cm tolerance for rounding errors

        // Top margin - STRICT
        $totalChecks++;
        $expectedTop = $rules['margin_top'] ?? 2.5;
        $actualTop = $margins['top'] ?? 0;
        $topPassed = abs($actualTop - $expectedTop) <= $marginTolerance;
        if ($topPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'margin_top',
            'label' => 'ระยะขอบบน',
            'expected' => $expectedTop . ' ซม. (ต้องตรงเป๊ะ)',
            'actual' => $actualTop > 0 ? round($actualTop, 2) . ' ซม.' : 'ไม่พบ',
            'passed' => $topPassed,
            'deduction' => $topPassed ? 0 : 1,
        ];

        // Left margin - STRICT
        $totalChecks++;
        $expectedLeft = $rules['margin_left'] ?? 3.0;
        $actualLeft = $margins['left'] ?? 0;
        $leftPassed = abs($actualLeft - $expectedLeft) <= $marginTolerance;
        if ($leftPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'margin_left',
            'label' => 'ระยะขอบซ้าย',
            'expected' => $expectedLeft . ' ซม. (ต้องตรงเป๊ะ)',
            'actual' => $actualLeft > 0 ? round($actualLeft, 2) . ' ซม.' : 'ไม่พบ',
            'passed' => $leftPassed,
            'deduction' => $leftPassed ? 0 : 1,
        ];

        // Bottom margin - STRICT
        $totalChecks++;
        $expectedBottom = $rules['margin_bottom'] ?? 2.0;
        $actualBottom = $margins['bottom'] ?? 0;
        $bottomPassed = abs($actualBottom - $expectedBottom) <= $marginTolerance;
        if ($bottomPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'margin_bottom',
            'label' => 'ระยะขอบล่าง',
            'expected' => $expectedBottom . ' ซม. (ต้องตรงเป๊ะ)',
            'actual' => $actualBottom > 0 ? round($actualBottom, 2) . ' ซม.' : 'ไม่พบ',
            'passed' => $bottomPassed,
            'deduction' => $bottomPassed ? 0 : 1,
        ];

        // Right margin - STRICT
        $totalChecks++;
        $expectedRight = $rules['margin_right'] ?? 2.0;
        $actualRight = $margins['right'] ?? 0;
        $rightPassed = abs($actualRight - $expectedRight) <= $marginTolerance;
        if ($rightPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'margin_right',
            'label' => 'ระยะขอบขวา',
            'expected' => $expectedRight . ' ซม. (ต้องตรงเป๊ะ)',
            'actual' => $actualRight > 0 ? round($actualRight, 2) . ' ซม.' : 'ไม่พบ',
            'passed' => $rightPassed,
            'deduction' => $rightPassed ? 0 : 1,
        ];

        // Check Paper Size
        $totalChecks++;
        $expectedPaper = $rules['paper_size'] ?? 'A4';
        $actualPaper = $extracted['paper_size'];
        $paperPassed = $actualPaper === $expectedPaper;
        if ($paperPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'paper_size',
            'label' => 'ขนาดกระดาษ',
            'expected' => $expectedPaper,
            'actual' => $actualPaper ?? 'ไม่ทราบ',
            'passed' => $paperPassed,
        ];

        // Check Orientation
        $totalChecks++;
        $expectedOrient = $rules['orientation'] ?? 'portrait';
        $actualOrient = $extracted['orientation'];
        $orientPassed = $actualOrient === $expectedOrient;
        if ($orientPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'orientation',
            'label' => 'แนวกระดาษ',
            'expected' => $expectedOrient === 'portrait' ? 'แนวตั้ง' : 'แนวนอน',
            'actual' => $actualOrient ? ($actualOrient === 'portrait' ? 'แนวตั้ง' : 'แนวนอน') : 'ไม่ทราบ',
            'passed' => $orientPassed,
        ];

        // STRICT MODE: Check Title "บันทึกข้อความ" formatting - must match EXACTLY
        $boldTexts = $extracted['bold_texts'] ?? [];
        $titleFound = false;
        $titleSizeCorrect = false;
        $actualTitleSize = null;

        foreach ($boldTexts as $boldItem) {
            if (strpos($boldItem['text'], 'บันทึกข้อความ') !== false) {
                $titleFound = true;
                $expectedTitleSize = $rules['title_font_size'] ?? 29;
                $actualTitleSize = $boldItem['size'];
                // STRICT: Title size must be EXACTLY the same - NO TOLERANCE
                if ($boldItem['size'] && $boldItem['size'] == $expectedTitleSize) {
                    $titleSizeCorrect = true;
                }
                break;
            }
        }

        $totalChecks++;
        $titlePassed = $titleFound && $titleSizeCorrect;
        if ($titlePassed)
            $passedCount++;
        $checks[] = [
            'element' => 'title_format',
            'label' => 'หัวเรื่อง "บันทึกข้อความ"',
            'expected' => 'ตัวหนา, ' . ($rules['title_font_size'] ?? 29) . ' pt (ต้องตรงเป๊ะ)',
            'actual' => $titleFound ?
                ('ตัวหนา, ' . ($actualTitleSize ?? 'ไม่ระบุ') . ' pt' . ($titleSizeCorrect ? ' ✓' : ' ✗')) :
                'ไม่พบ',
            'passed' => $titlePassed,
            'deduction' => $titlePassed ? 0 : 1,
        ];

        // Check Alignment
        $totalChecks++;
        $expectedAlign = $rules['alignment'];
        $actualAlign = $extracted['primary_alignment'];
        $alignPassed = $actualAlign && ($actualAlign === $expectedAlign || $actualAlign === 'both' || $actualAlign === 'justify');
        if ($alignPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'alignment',
            'label' => 'การจัดข้อความ',
            'expected' => $this->translateAlignment($expectedAlign),
            'actual' => $actualAlign ? $this->translateAlignment($actualAlign) : 'ไม่พบ',
            'passed' => $alignPassed,
        ];

        // STRICT MODE: Check First Line Indent - must match EXACTLY
        $totalChecks++;
        $expectedIndent = $rules['first_line_indent'] ?? 2.5; // cm
        $actualIndentTwips = $extracted['primary_indent'] ?? 0;
        // Convert twips to cm: 1 cm = 567 twips
        $actualIndentCm = $actualIndentTwips > 0 ? $actualIndentTwips / 567 : 0;
        // STRICT: Only allow 0.1cm tolerance for rounding errors
        $indentPassed = abs($actualIndentCm - $expectedIndent) <= 0.1;
        if ($indentPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'first_line_indent',
            'label' => 'ระยะย่อหน้าแรก',
            'expected' => $expectedIndent . ' ซม. (ต้องตรงเป๊ะ)',
            'actual' => $actualIndentCm > 0 ? round($actualIndentCm, 2) . ' ซม.' : 'ไม่พบ',
            'passed' => $indentPassed,
            'deduction' => $indentPassed ? 0 : 1,
        ];

        // Check Line Spacing (single = 1.0)
        $totalChecks++;
        $expectedLineSpacing = $rules['line_spacing'] ?? 1.0;
        $actualLineSpacing = $extracted['primary_line_spacing'];
        // Line spacing can be expressed as multiplier (1.0, 1.5) or exact points
        // PhpWord returns line height as multiplier or null
        $lineSpacingPassed = false;
        $lineSpacingActualText = 'ไม่พบ';
        if ($actualLineSpacing !== null) {
            // Check if it's close to 1.0 (single) - allow tolerance
            if (is_numeric($actualLineSpacing)) {
                if ($actualLineSpacing <= 1.15) {
                    $lineSpacingPassed = true;
                    $lineSpacingActualText = 'หนึ่งบรรทัด (1.0)';
                } elseif ($actualLineSpacing <= 1.6) {
                    $lineSpacingActualText = '1.5 บรรทัด';
                } else {
                    $lineSpacingActualText = 'สองบรรทัด (2.0)';
                }
            }
        }
        if ($lineSpacingPassed)
            $passedCount++;
        $checks[] = [
            'element' => 'line_spacing',
            'label' => 'ระยะห่างบรรทัด',
            'expected' => 'หนึ่งบรรทัด (1.0)',
            'actual' => $lineSpacingActualText,
            'passed' => $lineSpacingPassed,
        ];

        // STRICT MODE: Check Header Labels - must be bold with EXACT size
        $headerLabels = $rules['header_labels'] ?? ['ส่วนราชการ', 'ที่', 'วันที่', 'เรื่อง'];
        $expectedHeaderSize = $rules['header_font_size'] ?? 20;
        $boldTextsForHeaders = $extracted['bold_texts'] ?? [];

        $foundLabels = [];
        $correctLabels = [];
        $wrongLabels = [];

        foreach ($headerLabels as $label) {
            foreach ($boldTextsForHeaders as $boldItem) {
                if (strpos($boldItem['text'], $label) !== false) {
                    $foundLabels[] = $label;
                    // STRICT: Header size must be EXACTLY the same - NO TOLERANCE
                    if ($boldItem['size'] && $boldItem['size'] == $expectedHeaderSize) {
                        $correctLabels[] = $label;
                    } else {
                        $wrongLabels[] = $label . ' (' . ($boldItem['size'] ?? '?') . 'pt)';
                    }
                    break;
                }
            }
        }

        // STRICT: ALL headers must be correct
        $totalChecks++;
        $headersPassed = count($correctLabels) >= count($headerLabels);
        if ($headersPassed)
            $passedCount++;

        $actualMessage = 'ไม่พบหัวข้อ';
        if (count($foundLabels) > 0) {
            $actualMessage = 'พบ ' . count($correctLabels) . '/' . count($headerLabels) . ' หัวข้อถูกต้อง';
            if (count($wrongLabels) > 0) {
                $actualMessage .= ' (ผิด: ' . implode(', ', $wrongLabels) . ')';
            }
        }

        $checks[] = [
            'element' => 'header_labels',
            'label' => 'หัวข้อเอกสาร (ส่วนราชการ/ที่/วันที่/เรื่อง)',
            'expected' => 'ตัวหนา, ' . $expectedHeaderSize . ' pt (ต้องตรงเป๊ะ)',
            'actual' => $actualMessage,
            'passed' => $headersPassed,
            'deduction' => $headersPassed ? 0 : count($headerLabels) - count($correctLabels),
        ];

        $score = $totalChecks > 0 ? round(($passedCount / $totalChecks) * 100, 2) : 0;

        return [
            'score' => $score,
            'passed' => $passedCount,
            'total' => $totalChecks,
            'checks' => $checks,
            'raw' => $extracted,
        ];
    }

    /**
     * Translate alignment value to Thai.
     */
    private function translateAlignment(?string $alignment): string
    {
        return match ($alignment) {
            'left' => 'ชิดซ้าย',
            'right' => 'ชิดขวา',
            'center' => 'กึ่งกลาง',
            'both', 'justify' => 'เต็มแนว (Justify)',
            default => $alignment ?? 'ไม่ระบุ',
        };
    }

    /**
     * Compare numeral formats between submitted and master text.
     * Checks if Thai numerals (๐-๙) or Arabic numerals (0-9) are used consistently.
     */
    public function compareNumeralFormats(string $submittedText, string $masterText): array
    {
        $masterHasThai = $this->containsThaiNumerals($masterText);
        $masterHasArabic = $this->containsArabicNumerals($masterText);
        $submittedHasThai = $this->containsThaiNumerals($submittedText);
        $submittedHasArabic = $this->containsArabicNumerals($submittedText);

        $passed = true;
        $details = [];

        // If master uses Thai numerals, submitted should too
        if ($masterHasThai && !$submittedHasThai && $submittedHasArabic) {
            $passed = false;
            $details[] = 'ต้นฉบับใช้เลขไทย แต่งานที่ส่งใช้เลขอารบิก';
        }

        // If master uses Arabic numerals, submitted should too
        if ($masterHasArabic && !$submittedHasArabic && $submittedHasThai) {
            $passed = false;
            $details[] = 'ต้นฉบับใช้เลขอารบิก แต่งานที่ส่งใช้เลขไทย';
        }

        // Detect what type of numerals are used
        $masterType = 'ไม่มีตัวเลข';
        if ($masterHasThai && $masterHasArabic) {
            $masterType = 'เลขไทยและเลขอารบิก';
        } elseif ($masterHasThai) {
            $masterType = 'เลขไทย';
        } elseif ($masterHasArabic) {
            $masterType = 'เลขอารบิก';
        }

        $submittedType = 'ไม่มีตัวเลข';
        if ($submittedHasThai && $submittedHasArabic) {
            $submittedType = 'เลขไทยและเลขอารบิก';
        } elseif ($submittedHasThai) {
            $submittedType = 'เลขไทย';
        } elseif ($submittedHasArabic) {
            $submittedType = 'เลขอารบิก';
        }

        return [
            'passed' => $passed,
            'master_type' => $masterType,
            'submitted_type' => $submittedType,
            'details' => $details,
        ];
    }

    /**
     * Check if text contains Thai numerals (๐-๙).
     */
    private function containsThaiNumerals(string $text): bool
    {
        return preg_match('/[๐-๙]/', $text) === 1;
    }

    /**
     * Check if text contains Arabic numerals (0-9).
     */
    private function containsArabicNumerals(string $text): bool
    {
        return preg_match('/[0-9]/', $text) === 1;
    }
}
