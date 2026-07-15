import os
import urllib.parse

repo_dir = "."

readme_content = """# 🚀 Coding Interview Solutions
A comprehensive collection of my data structures and algorithms solutions, neatly organized by platform and topic tags!

<div align="center">
  <img src="./leetcode-stats.svg" alt="LeetCode Stats" />
</div>

"""

for platform in ["LeetCode", "GeeksForGeeks"]:
    platform_dir = os.path.join(repo_dir, platform)
    if not os.path.exists(platform_dir):
        continue
    
    readme_content += f"## 📁 {platform}\n\n"
    
    items = sorted(os.listdir(platform_dir))
    
    if platform == "GeeksForGeeks":
        readme_content += "| Problem |\n| ------- |\n"
        for item in items:
            item_dir = os.path.join(platform_dir, item)
            if not os.path.isdir(item_dir):
                continue
            encoded_problem = urllib.parse.quote(item)
            readme_content += f"| [{item}](./{platform}/{encoded_problem}) |\n"
        readme_content += "\n"
    else:
        for topic in items:
            topic_dir = os.path.join(platform_dir, topic)
            if not os.path.isdir(topic_dir):
                continue
            
            readme_content += f"### {topic.replace('-', ' ')}\n"
            readme_content += "| Problem |\n| ------- |\n"
            
            problems = sorted(os.listdir(topic_dir))
            for problem in problems:
                if os.path.isdir(os.path.join(topic_dir, problem)):
                    encoded_topic = urllib.parse.quote(topic)
                    encoded_problem = urllib.parse.quote(problem)
                    readme_content += f"| [{problem}](./{platform}/{encoded_topic}/{encoded_problem}) |\n"
            
            readme_content += "\n"

with open(os.path.join(repo_dir, "README.md"), "w") as f:
    f.write(readme_content)
