import os
import re
import json
import zipfile
import xml.etree.ElementTree as ET
from flask import Flask, request, jsonify

app = Flask(__name__)

def extract_text(file_obj):
    text = []
    try:
        with zipfile.ZipFile(file_obj, 'r') as z:
            content = z.read('word/document.xml')
            tree = ET.fromstring(content)
            # The namespace for Word XML
            ns = {'w': 'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
            for p in tree.findall('.//w:p', ns):
                para_text = ""
                for t in p.findall('.//w:t', ns):
                    if t.text:
                        para_text += str(t.text)
                text.append(para_text)
    except Exception as e:
        return ""
    return "\n".join(text).strip()

def normalize_text(text):
    text = re.sub(r'[\u200B-\u200D\uFEFF]', '', text)
    text = re.sub(r'\u00A0', ' ', text)
    text = re.sub(r'\r\n|\r', '\n', text)
    return text

def analyze_whitespace(submitted, master):
    def count_matches(pattern, text):
        return len(re.findall(pattern, text))
    
    m_spaces = count_matches(r' ', master)
    s_spaces = count_matches(r' ', submitted)
    m_tabs = count_matches(r'\t', master)
    s_tabs = count_matches(r'\t', submitted)
    m_nl = count_matches(r'\n', master)
    s_nl = count_matches(r'\n', submitted)
    m_ds = count_matches(r'  +', master)
    s_ds = count_matches(r'  +', submitted)
    
    m_lines = master.split('\n')
    s_lines = submitted.split('\n')
    
    m_ls, m_ts, s_ls, s_ts = 0, 0, 0, 0
    for l in m_lines:
        m = re.match(r'^( +)', l)
        if m: m_ls += len(m.group(1))
        m_t = re.search(r'( +)$', l)
        if m_t: m_ts += len(m_t.group(1))
        
    for l in s_lines:
        m = re.match(r'^( +)', l)
        if m: s_ls += len(m.group(1))
        m_t = re.search(r'( +)$', l)
        if m_t: s_ts += len(m_t.group(1))
    
    space_diff = abs(m_spaces - s_spaces)
    tab_diff = abs(m_tabs - s_tabs)
    nl_diff = abs(m_nl - s_nl)
    total_errors = space_diff + tab_diff + nl_diff
    
    return {
        'passed': total_errors == 0,
        'total_errors': total_errors,
        'master_spaces': m_spaces,
        'submitted_spaces': s_spaces,
        'space_diff': space_diff,
        'master_tabs': m_tabs,
        'submitted_tabs': s_tabs,
        'tab_diff': tab_diff,
        'master_line_breaks': m_nl,
        'submitted_line_breaks': s_nl,
        'line_break_diff': nl_diff,
        'master_double_spaces': m_ds,
        'submitted_double_spaces': s_ds,
        'double_space_diff': abs(m_ds - s_ds),
        'master_leading_spaces': m_ls,
        'submitted_leading_spaces': s_ls,
        'leading_space_diff': abs(m_ls - s_ls),
        'master_trailing_spaces': m_ts,
        'submitted_trailing_spaces': s_ts,
        'trailing_space_diff': abs(m_ts - s_ts),
        'master_lines': len(m_lines),
        'submitted_lines': len(s_lines)
    }

def lcs_length(a, b):
    m, n = len(a), len(b)
    prev = [0] * (n + 1)
    curr = [0] * (n + 1)
    
    for i in range(1, m + 1):
        for j in range(1, n + 1):
            if a[i - 1] == b[j - 1]:
                curr[j] = prev[j - 1] + 1
            else:
                curr[j] = max(prev[j], curr[j - 1])
        prev = curr
        curr = [0] * (n + 1)
    return prev[n]

@app.route('/grade', methods=['POST'])
def grade():
    if 'submitted_file' not in request.files or 'master_file' not in request.files:
        return jsonify({"error": "Missing files"}), 400
        
    submitted_file = request.files['submitted_file']
    master_file = request.files['master_file']
    
    try:
        submitted_text = extract_text(submitted_file)
        master_text = extract_text(master_file)
        
        sub_norm = normalize_text(submitted_text)
        mast_norm = normalize_text(master_text)
        
        ws_analysis = analyze_whitespace(sub_norm, mast_norm)
        
        sub_chars = list(sub_norm)
        mast_chars = list(mast_norm)
        
        total_mast_chars = len(mast_chars)
        total_sub_chars = len(sub_chars)
        
        if total_mast_chars == 0:
            return jsonify({
                "accuracy": 0, "correct_chars": 0, "total_chars": 0,
                "wrong_chars": 0, "missing_chars": 0, "extra_chars": total_sub_chars,
                "correct_words": 0, "total_words": 0, "wrong_words": 0,
                "missing_words": 0, "extra_words": 0, "whitespace_analysis": ws_analysis,
                "details": [], "submitted_text": sub_norm
            })
            
        correct_chars = lcs_length(mast_chars, sub_chars)
        wrong_chars = total_mast_chars - correct_chars
        missing_chars = max(0, total_mast_chars - total_sub_chars)
        extra_chars = max(0, total_sub_chars - total_mast_chars)
        
        accuracy = (correct_chars / total_mast_chars) * 100
        
        sub_words = [w for w in re.split(r'\s+', sub_norm) if w]
        mast_words = [w for w in re.split(r'\s+', mast_norm) if w]
        
        total_mast_words = len(mast_words)
        total_sub_words = len(sub_words)
        
        min_words = min(total_mast_words, total_sub_words)
        correct_words = sum(1 for i in range(min_words) if mast_words[i] == sub_words[i])
        
        result = {
            "accuracy": round(accuracy, 2),
            "correct_chars": correct_chars,
            "total_chars": total_mast_chars,
            "wrong_chars": wrong_chars,
            "missing_chars": missing_chars,
            "extra_chars": extra_chars,
            "correct_words": correct_words,
            "total_words": total_mast_words,
            "wrong_words": total_mast_words - correct_words,
            "missing_words": max(0, total_mast_words - total_sub_words),
            "extra_words": max(0, total_sub_words - total_mast_words),
            "whitespace_analysis": ws_analysis,
            "details": [],
            "submitted_text": submitted_text
        }
        return jsonify(result)
        
    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == '__main__':
    port = int(os.environ.get("PORT", 8000))
    app.run(host='0.0.0.0', port=port)
