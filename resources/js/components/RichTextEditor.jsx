import { useColorScheme, useDidUpdate } from "@mantine/hooks";
import { RichTextEditor as Editor, Link } from "@mantine/tiptap";
import Highlight from "@tiptap/extension-highlight";
import Placeholder from "@tiptap/extension-placeholder";
import Underline from "@tiptap/extension-underline";
import { useEditor } from "@tiptap/react";
import StarterKit from "@tiptap/starter-kit";
import classes from "./css/RichTextEditor.module.css";

export default function RichTextEditor({
  onChange,
  placeholder,
  content,
  height = 200,
  readOnly = false,
  ...props
}) {
  const editor = useEditor({
    editable: !readOnly,
    extensions: [
      StarterKit,
      Underline,
      Link,
      Highlight,
      Placeholder.configure({ placeholder }),
    ],
    content,
    onUpdate({ editor }) {
      onChange(editor.getHTML());
    },
  });

  useDidUpdate(() => {
    editor.commands.setContent(content);
  }, [content]);

  const colorScheme = useColorScheme();

  return (
    <Editor editor={editor} {...props}>
      <Editor.Toolbar sticky stickyOffset={60}>
        <Editor.ControlsGroup>
          <Editor.Bold />
          <Editor.Italic />
          <Editor.Underline />
          <Editor.Strikethrough />
          <Editor.Highlight />
        </Editor.ControlsGroup>

        <Editor.ControlsGroup>
          <Editor.BulletList />
          <Editor.OrderedList />
        </Editor.ControlsGroup>

        <Editor.ControlsGroup>
          <Editor.Link />
          <Editor.Unlink />
        </Editor.ControlsGroup>

        <Editor.ControlsGroup>
          <Editor.Code />
          <Editor.Blockquote />
        </Editor.ControlsGroup>
      </Editor.Toolbar>

      <Editor.Content
        bg={colorScheme === "dark" ? "dark.6" : "white"}
        className={classes.content}
        style={{ "--rich-text-editor-height": `${height}px` }}
      />
    </Editor>
  );
}
