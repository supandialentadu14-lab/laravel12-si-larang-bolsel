
import os
import re

base_path = r'd:\Laravel\SI-LARANG'
views_dir = os.path.join(base_path, 'resources', 'views')
app_dir = os.path.join(base_path, 'app')
routes_dir = os.path.join(base_path, 'routes')

# Get all view files
view_files = []
for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            full_path = os.path.join(root, file)
            rel_path = os.path.relpath(full_path, views_dir)
            view_name = rel_path.replace('.blade.php', '').replace(os.sep, '.')
            view_files.append({
                'name': view_name,
                'path': full_path
            })

# Files to search in
search_targets = []
for root, dirs, files in os.walk(app_dir):
    for file in files:
        if file.endswith('.php'):
            search_targets.append(os.path.join(root, file))
for root, dirs, files in os.walk(views_dir):
    for file in files:
        if file.endswith('.blade.php'):
            search_targets.append(os.path.join(root, file))
for root, dirs, files in os.walk(routes_dir):
    for file in files:
        if file.endswith('.php'):
            search_targets.append(os.path.join(root, file))

# Pre-read all search target contents
target_contents = {}
for target in search_targets: 
    try:
        with open(target, 'r', encoding='utf-8', errors='ignore') as f:
            target_contents[target] = f.read()
    except:
        pass

unused_views = []
used_views = []

for view in view_files:
    name = view['name']
    found = False
    
    # Simple string match for static view names
    # Note: This is an approximation. Dynamic views like view("folder.$var") are hard to catch.
    # We look for 'name', "name", or name in common view triggers
    patterns = [
        f"'{name}'",
        f'"{name}"',
        f"view({name})", # Rare but possible if name is a constant
        f"extends({name})",
        f"include({name})",
        f"component({name})"
    ]
    
    # Also check for partial matches if it's a directory? No, Laravel usually uses full dot notation.
    # However, some might use partials.modals (without specific modal sub-view if used dynamically)
    
    for content in target_contents.values():
        if any(p in content for p in [f"'{name}'", f'"{name}"']):
            found = True
            break
            
    if not found:
        # Check for dynamic usage potential (e.g. view("folder." . $page))
        # This is a bit risky to mark as unused if the folder prefix is used.
        parts = name.split('.')
        if len(parts) > 1:
            prefix = ".".join(parts[:-1]) + "."
            for content in target_contents.values():
                if f"'{prefix}'" in content or f'"{prefix}"' in content:
                    # Might be dynamic. Let's be cautious.
                    # found = True
                    pass
    
    if found:
        used_views.append(name)
    else:
        unused_views.append(name)

print(f"Total views: {len(view_files)}")
print(f"Potentially unused views: {len(unused_views)}")
for v in sorted(unused_views):
    print(v)
