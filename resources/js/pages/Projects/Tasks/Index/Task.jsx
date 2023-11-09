import useTasksStore from "@/hooks/useTasksStore";
import { shortName } from "@/services/UserService";
import { Draggable } from "@hello-pangea/dnd";
import { Link } from "@inertiajs/react";
import { Checkbox, Flex, Group, Pill, Text, rem } from "@mantine/core";
import { IconGripVertical } from "@tabler/icons-react";
import classes from "./css/Task.module.css";

export default function Task({ task, index }) {
  const complete = useTasksStore((state) => state.complete);

  const toggleCompleted = (state) => {
    complete(task, state);
  };

  return (
    <Draggable draggableId={"task-" + task.id} index={index}>
      {(provided, snapshot) => (
        <Flex
          {...provided.draggableProps}
          ref={provided.innerRef}
          className={`${classes.task} ${
            snapshot.isDragging && classes.itemDragging
          } ${task.completed_at !== null && classes.completed}`}
        >
          <Group gap="sm">
            <div {...provided.dragHandleProps}>
              <IconGripVertical
                style={{ width: rem(18), height: rem(18) }}
                stroke={1.5}
                className={classes.dragHandle}
              />
            </div>

            <Checkbox
              size="sm"
              radius="xl"
              color="green"
              checked={task.completed_at !== null}
              onChange={(e) => toggleCompleted(e.currentTarget.checked)}
              className={classes.checkbox}
            />
            {task.assigned_to_user && (
              <Link href={route("users.edit", task.assigned_to_user.id)}>
                <Pill size="sm" className={classes.user}>
                  {shortName(task.assigned_to_user.name)}
                </Pill>
              </Link>
            )}
            <Text key={task.id} size="sm" fw={500}>
              #{task.number + ": " + task.name}
            </Text>
          </Group>
        </Flex>
      )}
    </Draggable>
  );
}