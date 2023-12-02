import { Center, Group, Text, rem } from "@mantine/core";
import { IconChartArcs } from "@tabler/icons-react";

export default function Logo(props) {
  return (
    <Group wrap="nowrap" {...props}>
      <Center bg="blue" p={5} style={{ borderRadius: "100%" }}>
        <IconChartArcs style={{ width: rem(25), height: rem(25), flexShrink: 0 }} />
      </Center>
      <Text fz={20} fw={600}>
        LaraCollab
      </Text>
    </Group>
  );
}
