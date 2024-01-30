import ActionButton from '@/components/ActionButton';
import BackButton from '@/components/BackButton';
import useForm from '@/hooks/useForm';
import ContainerBox from '@/layouts/ContainerBox';
import Layout from '@/layouts/MainLayout';
import { redirectTo } from '@/utils/route';
import {
  Anchor,
  Breadcrumbs,
  Grid,
  Group,
  NumberInput,
  Select,
  TextInput,
  Textarea,
  Title,
} from '@mantine/core';
import { useEffect, useState } from 'react';

const ProjectCreate = ({ dropdowns: { companies, currencies } }) => {
  const [currencySymbol, setCurrencySymbol] = useState();

  const [form, submit, updateValue] = useForm('post', route('projects.store'), {
    name: '',
    description: '',
    rate: 0,
    client_company_id: '',
  });

  useEffect(() => {
    let symbol = currencies.find(i =>
      i.client_companies.find(c => c.id.toString() === form.data.client_company_id)
    )?.symbol;

    if (symbol) {
      setCurrencySymbol(symbol);
    }
  }, [form.data.client_company_id]);

  return (
    <>
      <Breadcrumbs
        fz={14}
        mb={30}
      >
        <Anchor
          href='#'
          onClick={() => redirectTo('projects.index')}
          fz={14}
        >
          Projects
        </Anchor>
        <div>Create</div>
      </Breadcrumbs>

      <Grid
        justify='space-between'
        align='flex-end'
        gutter='xl'
        mb='lg'
      >
        <Grid.Col span='auto'>
          <Title order={1}>Create project</Title>
        </Grid.Col>
        <Grid.Col span='content'></Grid.Col>
      </Grid>

      <ContainerBox maw={500}>
        <form onSubmit={submit}>
          <TextInput
            label='Name'
            placeholder='Project name'
            required
            mt='md'
            value={form.data.name}
            onChange={e => updateValue('name', e.target.value)}
            error={form.errors.name}
          />

          <Textarea
            label='Description'
            placeholder='Project description'
            mt='md'
            autosize
            minRows={4}
            maxRows={8}
            value={form.data.description}
            onChange={e => updateValue('description', e.target.value)}
          />

          <Select
            label='Company requesting work'
            placeholder='Select company'
            required
            mt='md'
            value={form.data.client_company_id}
            onChange={value => updateValue('client_company_id', value)}
            data={companies}
            error={form.errors.client_company_id}
          />

          <NumberInput
            label='Hourly rate'
            mt='md'
            allowNegative={false}
            clampBehavior='strict'
            decimalScale={2}
            fixedDecimalScale={true}
            prefix={currencySymbol}
            value={form.data.rate}
            onChange={value => updateValue('rate', value)}
            error={form.errors.rate}
          />

          <Group
            justify='space-between'
            mt='xl'
          >
            <BackButton route='projects.index' />
            <ActionButton loading={form.processing}>Create</ActionButton>
          </Group>
        </form>
      </ContainerBox>
    </>
  );
};

ProjectCreate.layout = page => <Layout title='Create project'>{page}</Layout>;

export default ProjectCreate;
